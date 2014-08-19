<?php

namespace spec\Nfq\Fairytale\ApiBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AuthorizationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\EventListener\Authorization');
    }
}
