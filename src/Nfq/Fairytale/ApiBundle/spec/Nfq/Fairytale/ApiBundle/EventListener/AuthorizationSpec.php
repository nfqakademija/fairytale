<?php

namespace spec\Nfq\Fairytale\ApiBundle\EventListener;

use Nfq\Fairytale\ApiBundle\Controller\ApiControllerInterface;
use Nfq\Fairytale\ApiBundle\EventListener\Authorization;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @mixin Authorization
 */
class AuthorizationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\EventListener\Authorization');
    }

    function it_should_log_event(
        FilterControllerEvent $event,
        ApiControllerInterface $ctrl,
        LoggerInterface $logger,
        SecurityContext $security,
        Request $request,
        TokenInterface $token
    ) {
        $logger->info('API call', Argument::type('array'))->shouldBeCalled();

        $token->getRoles()->willReturn([]);
        $token->getUsername()->willReturn('anon.');

        $security->getToken()->willReturn($token);

        $this->setContext($security);
        $this->setLogger($logger);

        $request->getRequestUri()->willReturn('/api/user/1');
        $request->getMethod()->willReturn('GET');

        $event->getController()->willReturn([$ctrl, 'someAction']);
        $event->getRequest()->willReturn($request);

        $this->onKernelController($event);
    }
}
