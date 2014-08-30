<?php

namespace Nfq\Fairytale\ApiBundle\EventListener;

use JMS\Serializer\Serializer;
use Nfq\Fairytale\ApiBundle\Controller\ApiControllerInterface;
use Nfq\Fairytale\ApiBundle\Helper\ResourceResolver;
use Nfq\Fairytale\ApiBundle\Security\CredentialStore;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Security\Core\SecurityContext;

class LoggingListener implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var  SecurityContext */
    protected $context;

    /**
     * @param SecurityContext $context
     */
    public function setContext(SecurityContext $context)
    {
        $this->context = $context;
    }

    /**
     * @return string[]
     */
    private function getRoles()
    {
        return array_map(
            function (RoleInterface $role) {
                return $role->getRole();
            },
            $this->context->getToken()->getRoles()
        );
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        if ($event->getController()[0] instanceof ApiControllerInterface) {
            $this->logger && $this->logger->info(
                'API call',
                [
                    'url'            => $event->getRequest()->getRequestUri(),
                    'method'         => $event->getRequest()->getMethod(),
                    'security_user'  => $this->context->getToken()->getUsername(),
                    'security_roles' => $this->getRoles(),
                ]
            );
        }
    }
}
