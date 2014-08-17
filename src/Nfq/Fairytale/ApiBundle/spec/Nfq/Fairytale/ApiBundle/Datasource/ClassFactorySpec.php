<?php

namespace spec\Nfq\Fairytale\ApiBundle\Datasource;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClassFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\Datasource\ClassFactory');
    }

    function it_should_create_class()
    {
        $this->create('stdClass')->shouldHaveType('stdClass');
    }
}
