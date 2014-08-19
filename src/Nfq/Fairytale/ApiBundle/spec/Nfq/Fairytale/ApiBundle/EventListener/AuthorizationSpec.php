<?php

namespace spec\Nfq\Fairytale\ApiBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class AuthorizationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\EventListener\Authorization');
    }

    function it_should_handle_event(FilterControllerEvent $event)
    {
        $this->onKernelController($event)->shouldBe(null);
    }
}
