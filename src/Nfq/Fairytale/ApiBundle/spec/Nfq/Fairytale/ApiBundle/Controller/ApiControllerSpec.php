<?php

namespace spec\Nfq\Fairytale\ApiBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ApiControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\Controller\ApiController');
    }

    function it_can_read_resource()
    {
        $this->readAction('resource', 1)->shouldBeArray();
    }

    function it_can_index_resource()
    {
        $this->indexAction('resource')->shouldBeArray();
    }

    function it_can_update_resource()
    {
        $this->updateAction('resource', 1)->shouldBeArray();
    }

    function it_can_delete_resource()
    {
        $this->deleteAction('resource', 1)->shouldBeArray();
    }
}
