<?php

namespace Nfq\Fairytale\ApiBundle\EventListener;

use JMS\Serializer\SerializerInterface;
use Nfq\Fairytale\ApiBundle\Actions\ActionManager;
use Nfq\Fairytale\ApiBundle\Controller\ApiControllerInterface;
use Nfq\Fairytale\ApiBundle\Helper\ActionResolver;
use Nfq\Fairytale\ApiBundle\Helper\ResourceResolver;
use Nfq\Fairytale\ApiBundle\Security\CredentialStore;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\SecurityContextInterface;

class AuthorizationListener implements EventSubscriberInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    const API_REQUEST = 'api_request';
    const API_REQUEST_RESOURCE = 'resource';
    const API_REQUEST_ACTION = 'action';
    const API_REQUEST_PAYLOAD = 'payload';
    const API_REQUEST_RESPONSE = 'api_request.response';

    /** @var  SecurityContextInterface */
    protected $securityContext;

    /** @var  CredentialStore */
    protected $credentials;

    /** @var  ResourceResolver */
    protected $resourceResolver;

    /** @var  ActionManager */
    protected $actionResolver;

    /** @var  SerializerInterface */
    protected $serializer;

    /**
     * @param ResourceResolver $resolver
     */
    public function setResourceResolver(ResourceResolver $resolver)
    {
        $this->resourceResolver = $resolver;
    }

    /**
     * @param SecurityContextInterface $securityContext
     */
    public function setSecurityContext(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param ActionManager $actionResolver
     */
    public function setActionResolver(ActionManager $actionResolver)
    {
        $this->actionResolver = $actionResolver;
    }

    /**
     * @param CredentialStore $credentials
     */
    public function setCredentials(CredentialStore $credentials)
    {
        $this->credentials = $credentials;
    }

    public function decideRequestController(FilterControllerEvent $event)
    {
        if ($event->getController()[0] instanceof ApiControllerInterface) {
            $request = $event->getRequest();

            $resource = $this->resourceResolver->resolve($request->attributes->get('resource'));
            $action = $this->actionResolver->resolve(
                $resource,
                $request->attributes->get('actionName'),
                $request->getMethod(),
                null !== $request->attributes->get('identifier')
            );
            $content = $request->getContent();
            $payload = empty($content) ? null : $this->serializer->deserialize(
                $content,
                'array',
                $request->getRequestFormat('json')
            );

            $attributes = [
                self::API_REQUEST          => true,
                self::API_REQUEST_RESOURCE => $resource,
                self::API_REQUEST_ACTION   => $action,
                self::API_REQUEST_PAYLOAD  => $payload,
            ];

            $request->attributes->add($attributes);
        }
    }

    public function decideRequestFormat(FilterControllerEvent $event)
    {
        if ($this->isApiRequest($event->getRequest())) {
            if ($mime = $event->getRequest()->headers->get('Accept')) {
                if ($format = $event->getRequest()->getFormat($mime)) {
                    $event->getRequest()->setRequestFormat($format);
                }
            }
        }
    }

    public function validateRequest(FilterControllerEvent $event)
    {
        if ($this->isApiRequest($event->getRequest())) {
            $payload = $event->getRequest()->attributes->get(self::API_REQUEST_PAYLOAD);

            if ($payload) {
                $forbiddenFields = $this->getSecurityViolatingFields($event->getRequest(), $payload);

                if (!empty($forbiddenFields)) {
                    throw new AccessDeniedHttpException($event->getRequest()->getUri());
                }
            }
        }
    }

    public function validateResponse(GetResponseForControllerResultEvent $event)
    {
        if ($this->isApiRequest($event->getRequest())) {
            list($content, $code) = $event->getControllerResult();

            $rawContent = $this->serializer->deserialize(
                $this->serializer->serialize($content, 'json'),
                'array',
                'json'
            );

            $allowedFields = $this->getAllowedFields($event->getRequest());

            $filter = function ($singleItem) use ($allowedFields) {
                return array_intersect_key($singleItem, $allowedFields);
            };

            if (is_array($content)) {
                $filteredContent = array_map($filter, $rawContent);
            } else {
                $filteredContent = $filter($rawContent);
            }

            if (empty($filteredContent)) {
                throw new AccessDeniedHttpException();
            }

            $event->setControllerResult([$filteredContent, $code]);
        }
    }

    public function handleControllerResult(GetResponseForControllerResultEvent $event)
    {
        if ($this->isApiRequest($event->getRequest())) {
            list($content, $code) = $event->getControllerResult();

            $response = $this->buildResponse($event->getRequest(), $content, $code);

            $event->setResponse($response);
        }
    }

    public function handleException(GetResponseForExceptionEvent $event)
    {
        if ($this->isApiRequest($event->getRequest())) {

            $exception = $event->getException();

            switch (true) {
                case ($exception instanceof HttpExceptionInterface):
                    $response['code'] = $exception->getStatusCode();
                    $response['message'] = Response::$statusTexts[$exception->getStatusCode()];
                    break;

                default:
                    $response['code'] = 500;
                    $response['message'] = 'Internal Server Error';
                    break;
            }

            // setResponse() stops propagation, so we have to log this one manually

            /** @var \Exception $exception */
            $this->logException(
                $exception,
                sprintf(
                    'Uncaught PHP Exception %s: "%s" at %s line %s',
                    get_class($exception),
                    $exception->getMessage(),
                    $exception->getFile(),
                    $exception->getLine()
                )
            );

            $event->setResponse($this->buildResponse($event->getRequest(), $response, $response['code']));
        }
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => [
                ['decideRequestController', 30],
                ['decideRequestFormat', 20],
                ['validateRequest', 10],
            ],
            KernelEvents::VIEW       => [
                ['validateResponse', 30],
                ['handleControllerResult', 20],
            ],
            KernelEvents::EXCEPTION  => [
                ['handleException', 0]
            ]
        ];
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function isApiRequest(Request $request)
    {
        return $request->attributes->get(self::API_REQUEST, false);
    }

    /**
     * @param Request               $request
     * @param                       $data
     * @return array
     */
    private function getSecurityViolatingFields(Request $request, $data)
    {
        $fields = $this->getAllowedFields($request);
        return array_diff_key($data, $fields);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getAllowedFields(Request $request)
    {
        $fields = $this->credentials->getAccessibleFields(
            $this->securityContext->getToken()->getRoles(),
            $request->attributes->get(self::API_REQUEST_RESOURCE),
            $request->attributes->get(self::API_REQUEST_ACTION)
        );
        return $fields;
    }

    /**
     * @param Request  $request
     * @param          $content
     * @param          $code
     * @return Response
     */
    private function buildResponse(Request $request, $content, $code)
    {
        $format = $request->getRequestFormat('json');

        $request = new Response(
            $this->serializer->serialize($content, $format),
            $code,
            [
                'Content-type' => $request->getMimeType($format)
            ]
        );

        return $request;
    }

    /**
     * Borrowed from \Symfony\Component\HttpKernel\EventListener\ExceptionListener::logException
     * Logs an exception.
     *
     * @param \Exception $exception The original \Exception instance
     * @param string     $message   The error message to log
     * @param bool       $original  False when the handling of the exception thrown another exception
     */
    protected function logException(\Exception $exception, $message, $original = true)
    {
        $isCritical = !$exception instanceof HttpExceptionInterface || $exception->getStatusCode() >= 500;
        $context = ['exception' => $exception];
        if (null !== $this->logger) {
            if ($isCritical) {
                $this->logger->critical($message, $context);
            } else {
                $this->logger->error($message, $context);
            }
        } elseif (!$original || $isCritical) {
            // @codeCoverageIgnoreStart
            error_log($message);
            // @codeCoverageIgnoreStop
        }
    }
}
