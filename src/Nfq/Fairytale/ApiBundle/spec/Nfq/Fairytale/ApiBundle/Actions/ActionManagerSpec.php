<?php

namespace spec\Nfq\Fairytale\ApiBundle\Actions;

use Nfq\Fairytale\ApiBundle\Actions\ResourceActionInterface;
use Nfq\Fairytale\ApiBundle\Actions\ActionManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin ActionManager
 */
class ActionManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\Actions\ActionManager');
    }

    function it_should_allow_chaining_setters(ResourceActionInterface $action)
    {
        $this->addResourceAction($action, '', '', '')
            ->shouldHaveType('Nfq\Fairytale\ApiBundle\Actions\ActionManager');
    }

    function it_should_return_null_if_action_not_supported()
    {
        $resource = 'foo';
        $action = 'meta';
        $method = 'GET';

        $this->find($resource, $action, $method)->shouldBe(null);
    }

    function it_should_find_action(ResourceActionInterface $actionImpl)
    {
        $resource = 'foo';
        $action = 'meta';
        $method = 'GET';

        $this->addResourceAction($actionImpl, $resource, $action, $method);
        $this->find($resource, $action, $method)->shouldBe($actionImpl);
    }

    function it_should_find_action_for_any_resource(ResourceActionInterface $actionImpl)
    {
        $resource = 'foo';
        $action = 'meta';
        $method = 'GET';

        $this->addResourceAction($actionImpl, '*', $action, $method);
        $this->find($resource, $action, $method)->shouldBe($actionImpl);
    }
}
