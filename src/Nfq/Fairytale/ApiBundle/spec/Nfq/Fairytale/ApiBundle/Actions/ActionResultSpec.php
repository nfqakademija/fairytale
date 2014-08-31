<?php

namespace spec\Nfq\Fairytale\ApiBundle\Actions;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin \Nfq\Fairytale\ApiBundle\Actions\ActionResult
 */
class ActionResultSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\Actions\ActionResult');
    }
}
