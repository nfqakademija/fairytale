<?php

namespace spec\Nfq\Fairytale\ApiBundle\EventListener;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Nfq\Fairytale\ApiBundle\Actions\ActionInterface;
use Nfq\Fairytale\ApiBundle\Actions\ActionManager;
use Nfq\Fairytale\ApiBundle\Controller\ApiControllerInterface;
use Nfq\Fairytale\ApiBundle\EventListener\AuthorizationListener;
use Nfq\Fairytale\ApiBundle\Helper\ActionResolver;
use Nfq\Fairytale\ApiBundle\Helper\ResourceResolver;
use Nfq\Fairytale\ApiBundle\Security\CredentialStore;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @mixin AuthorizationListener
 */
class AuthorizationListenerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\EventListener\AuthorizationListener');
    }

    function it_should_recognize_and_mark_api_requests(
        Request $request,
        ParameterBag $attributes,
        ApiControllerInterface $controller,
        FilterControllerEvent $event,
        ResourceResolver $resourceResolver,
        ActionManager $actionResolver,
        SerializerInterface $serializer,
        ActionInterface $action
    ) {
        $allAttrs = [
            AuthorizationListener::API_REQUEST          => true,
            AuthorizationListener::API_REQUEST_ACTION   => $action,
            AuthorizationListener::API_REQUEST_RESOURCE => 'FooBundle:Bar',
            AuthorizationListener::API_REQUEST_PAYLOAD  => ['foo' => 'bar'],
        ];

        $attributes->add($allAttrs)->shouldBeCalled();
        $attributes->get('resource')->willReturn('bar');
        $attributes->get('actionName')->willReturn('baz');
        $attributes->get('identifier')->willReturn(1);

        $request->attributes = $attributes;
        $request->getContent()->willReturn('{"foo":"bar"}');
        $request->getRequestFormat('json')->willReturn('json');
        $request->getMethod()->willReturn('GET');

        $event->getController()->willReturn([$controller, 'fooAction']);
        $event->getRequest()->willReturn($request);

        $resourceResolver->resolve('bar')->willReturn('FooBundle:Bar');
        $actionResolver->resolve('FooBundle:Bar', 'baz', 'GET', true)->willReturn($action);

        $serializer->deserialize('{"foo":"bar"}', 'array', 'json')->willReturn(['foo' => 'bar']);

        $this->setSerializer($serializer);
        $this->setResourceResolver($resourceResolver);
        $this->setActionResolver($actionResolver);
        $this->decideRequestController($event);
    }

    function it_should_recognize_format(
        FilterControllerEvent $event,
        Request $request,
        ParameterBag $attributes,
        ParameterBag $headers
    ) {
        $attributes->get(AuthorizationListener::API_REQUEST, false)->willReturn(true);
        $request->attributes = $attributes;

        $format = uniqid();
        $mime = 'mime/' . $format;

        $headers->get('Accept')->willReturn($mime);
        $request->headers = $headers;
        $request->getFormat($mime)->willReturn($format);
        $request->setRequestFormat($format)->shouldBeCalled();

        $event->getRequest()->willReturn($request);

        $this->decideRequestFormat($event);
    }

    function it_should_validate_request_and_throw(
        FilterControllerEvent $event,
        CredentialStore $credentialStore,
        SecurityContext $securityContext,
        ActionInterface $action
    ) {
        $action->getName()->willReturn('action');

        $token = new AnonymousToken('', '', ['ROLE_USER']);
        $request = new Request();
        $attributes = new ParameterBag(
            [
                AuthorizationListener::API_REQUEST          => true,
                AuthorizationListener::API_REQUEST_RESOURCE => 'resource',
                AuthorizationListener::API_REQUEST_ACTION   => $action,
                AuthorizationListener::API_REQUEST_PAYLOAD  => [
                    'foo' => 'bar',
                    'baz' => 'qux',
                ]
            ]
        );

        $request->attributes = $attributes;
        $event->getRequest()->willReturn($request);

        $securityContext->getToken()->willReturn($token);

        $credentialStore->getAccessibleFields($token->getRoles(), 'resource', $action)
            ->willReturn(['foo' => 'ROLE_USER']);

        $this->setCredentials($credentialStore);
        $this->setSecurityContext($securityContext);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException')
            ->during('validateRequest', [$event]);
    }

    function it_should_validate_request_and_pass(
        FilterControllerEvent $event,
        CredentialStore $credentialStore,
        SecurityContext $securityContext,
        ActionInterface $action
    ) {
        $token = new AnonymousToken('', '', ['ROLE_USER']);
        $request = new Request();
        $attributes = new ParameterBag(
            [
                AuthorizationListener::API_REQUEST          => true,
                AuthorizationListener::API_REQUEST_RESOURCE => 'resource',
                AuthorizationListener::API_REQUEST_ACTION   => $action,
                AuthorizationListener::API_REQUEST_PAYLOAD  => [
                    'foo' => 'bar',
                    'baz' => 'qux',
                ]
            ]
        );

        $request->attributes = $attributes;
        $event->getRequest()->willReturn($request);

        $securityContext->getToken()->willReturn($token);

        $credentialStore->getAccessibleFields($token->getRoles(), 'resource', $action)->willReturn(
            [
                'foo' => 'ROLE_USER',
                'baz' => 'ROLE_USER',
            ]
        );

        $this->setCredentials($credentialStore);
        $this->setSecurityContext($securityContext);
        $this->validateRequest($event);
    }

    function it_should_validate_request_without_content(
        FilterControllerEvent $event,
        Request $request,
        ParameterBag $attributes
    ) {
        $attributes->get(AuthorizationListener::API_REQUEST, false)->willReturn(true);
        $attributes->get(AuthorizationListener::API_REQUEST_RESOURCE)->willReturn('resource');
        $attributes->get(AuthorizationListener::API_REQUEST_ACTION)->willReturn('action');
        $attributes->get(AuthorizationListener::API_REQUEST_PAYLOAD)->willReturn(null);
        $request->attributes = $attributes;
        $event->getRequest()->willReturn($request);

        $this->validateRequest($event);
    }

    function it_should_validate_response_and_pass(
        GetResponseForControllerResultEvent $event,
        CredentialStore $credentialStore,
        SecurityContextInterface $securityContext,
        SerializerInterface $serializer
    ) {
        $token = new AnonymousToken('', '', ['ROLE_USER']);
        $controllerResult = [(object)['foo' => 'bar', 'baz' => 'qux'], 200];
        $controllerResultJson = json_encode($controllerResult[0]);
        $controllerResultRaw = json_decode($controllerResultJson, true);

        $request = new Request();
        $attributes = new ParameterBag(
            [
                AuthorizationListener::API_REQUEST          => true,
                AuthorizationListener::API_REQUEST_RESOURCE => 'FooBundle:Bar',
                AuthorizationListener::API_REQUEST_ACTION   => 'baz'
            ]
        );
        $request->attributes = $attributes;

        $event->getRequest()->willReturn($request);
        $event->getControllerResult()->willReturn($controllerResult);

        $securityContext->getToken()->willReturn($token);

        $credentialStore->getAccessibleFields($token->getRoles(), 'FooBundle:Bar', 'baz')->willReturn(
            [
                'foo' => 'ROLE_USER',
                'baz' => 'ROLE_USER',
            ]
        );

        $serializer->serialize($controllerResult[0], 'json')->willReturn($controllerResultJson);
        $serializer->deserialize($controllerResultJson, 'array', 'json')->willReturn($controllerResultRaw);

        $event->setControllerResult([$controllerResultRaw, 200])->shouldBeCalled();

        $this->setSerializer($serializer);
        $this->setCredentials($credentialStore);
        $this->setSecurityContext($securityContext);
        $this->validateResponse($event);
    }

    function it_should_validate_response_and_throw(
        GetResponseForControllerResultEvent $event,
        CredentialStore $credentialStore,
        SecurityContextInterface $securityContext,
        SerializerInterface $serializer
    ) {
        $token = new AnonymousToken('', '', ['ROLE_USER']);
        $request = new Request();
        $attributes = new ParameterBag(
            [
                AuthorizationListener::API_REQUEST          => true,
                AuthorizationListener::API_REQUEST_RESOURCE => 'FooBundle:Bar',
                AuthorizationListener::API_REQUEST_ACTION   => 'baz'
            ]
        );
        $request->attributes = $attributes;

        $controllerResult = [(object)['foo' => 'bar', 'baz' => 'qux'], 200];
        $controllerResultJson = json_encode($controllerResult[0]);
        $controllerResultRaw = json_decode($controllerResultJson, true);

        $event->getRequest()->willReturn($request);
        $event->getControllerResult()->willReturn($controllerResult);

        $securityContext->getToken()->willReturn($token);

        $credentialStore->getAccessibleFields($token->getRoles(), 'FooBundle:Bar', 'baz')->willReturn([]);

        $serializer->serialize($controllerResult[0], 'json')->willReturn($controllerResultJson);
        $serializer->deserialize($controllerResultJson, 'array', 'json')->willReturn($controllerResultRaw);

        $this->setSerializer($serializer);
        $this->setCredentials($credentialStore);
        $this->setSecurityContext($securityContext);
        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException')
            ->during('validateResponse', [$event]);
    }

    function it_should_handle_controller_result(
        GetResponseForControllerResultEvent $event,
        Serializer $serializer
    ) {
        $request = new Request();
        $attributes = new ParameterBag([AuthorizationListener::API_REQUEST => true]);
        $request->attributes = $attributes;

        $request->setRequestFormat('json');

        $controllerResult = ['foo' => 'bar', 'baz' => 'qux'];
        $status = 200;
        $content = json_encode($controllerResult);

        $format = 'json';
        $mime = 'application/json';

        $event->getControllerResult()->willReturn([$controllerResult, $status]);

        $event->getRequest()->willReturn($request);

        $isValid = $this->isValidResponse($content, $status, $mime);
        $event->setResponse(Argument::that($isValid))->shouldBeCalled();

        $serializer->serialize($controllerResult, $format)->willReturn($content);

        $this->setSerializer($serializer);
        $this->handleControllerResult($event);
    }

    function it_should_handle_http_exception(
        GetResponseForExceptionEvent $event,
        LoggerInterface $logger,
        SerializerInterface $serializer
    ) {
        $exception = new HttpException(400, 'Very useful exception message');

        list($request, $context) = $this->setUpHandleExceptionTest($event, $logger, $serializer, $exception);

        $isValid = $this->isValidResponse('the content', 400, $request->getMimeType($request->getFormat('json')));
        $event->setResponse(Argument::that($isValid))->shouldBeCalled();

        $serializer->serialize(['code' => 400, 'message' => 'Bad Request'], 'json')
            ->willReturn('the content');

        $logger->error(Argument::type('string'), $context)->shouldBeCalled();

        $this->handleException($event);
    }

    function it_should_handle_exception(
        GetResponseForExceptionEvent $event,
        LoggerInterface $logger,
        SerializerInterface $serializer
    ) {
        $exception = new ContextErrorException('Very useful exception message', 0, 0, 'Foo.php', 42);

        list($request, $context) = $this->setUpHandleExceptionTest($event, $logger, $serializer, $exception);

        $isValid = $this->isValidResponse('the content', 500, $request->getMimeType($request->getFormat('json')));
        $event->setResponse(Argument::that($isValid))->shouldBeCalled();

        $serializer->serialize(['code' => 500, 'message' => 'Internal Server Error'], 'json')
            ->willReturn('the content');

        $logger->critical(Argument::type('string'), $context)->shouldBeCalled();

        $this->handleException($event);
    }

    protected function isValidResponse($content, $status, $contentType)
    {
        return function (Response $response) use ($content, $status, $contentType) {
            return $response->getContent() === $content
            && $response->headers->get('Content-type', [$contentType])
            && $response->getStatusCode() == $status;
        };
    }

    /**
     * @param GetResponseForExceptionEvent $event
     * @param LoggerInterface              $logger
     * @param SerializerInterface          $serializer
     * @param                              $exception
     * @return array
     */
    private function setUpHandleExceptionTest(
        GetResponseForExceptionEvent $event,
        LoggerInterface $logger,
        SerializerInterface $serializer,
        $exception
    ) {
        $request = new Request();
        $attributes = new ParameterBag([AuthorizationListener::API_REQUEST => true]);
        $request->attributes = $attributes;
        $this->setLogger($logger);
        $this->setSerializer($serializer);
        $event->getRequest()->willReturn($request);
        $event->getException()->willReturn($exception);
        $context = ['exception' => $exception];

        return [$request, $context];
    }
}
