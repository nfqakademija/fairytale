<?php

namespace Nfq\Fairytale\ApiBundle\EventListener;

use Nfq\Fairytale\ApiBundle\Controller\ApiControllerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Security\Core\SecurityContext;

class Authorization
{
    /** @var  SecurityContext */
    protected $context;
    /** @var  LoggerInterface */
    protected $logger;
    /** @var  CredentialStore */
    protected $credentials;

    /**
     * @param SecurityContext $context
     */
    public function setContext(SecurityContext $context)
    {
        $this->context = $context;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
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

            var_dump($this->getRoles());
        }
    }
}
