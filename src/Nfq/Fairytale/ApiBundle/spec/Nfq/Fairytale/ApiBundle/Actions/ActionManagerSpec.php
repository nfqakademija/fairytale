<?php

namespace spec\Nfq\Fairytale\ApiBundle\Actions;

use Nfq\Fairytale\ApiBundle\Actions\ActionManager;
use Nfq\Fairytale\ApiBundle\Actions\Collection\CollectionActionInterface;
use Nfq\Fairytale\ApiBundle\Actions\Instance\InstanceActionInterface;
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

    function it_should_allow_chaining_setters(CollectionActionInterface $action)
    {
        $this->addCollectionAction($action, '', '', '')
            ->shouldHaveType('Nfq\Fairytale\ApiBundle\Actions\ActionManager');
    }

    function it_should_throw_if_action_not_supported()
    {
        $resource = 'foo';
        $action = 'meta';
        $method = 'GET';

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\BadRequestHttpException')
            ->during('resolve', [$resource, $action, $method]);
    }

    function it_should_find_action(CollectionActionInterface $actionImpl1, InstanceActionInterface $actionImpl2)
    {
        $resource = 'foo';
        $action = 'meta';
        $method = 'GET';

        $this->addCollectionAction($actionImpl1, $resource, $action, $method);
        $this->addInstanceAction($actionImpl2, $resource, $action, $method);

        $this->resolve($resource, $action, $method)->shouldBe($actionImpl1);
        $this->resolve($resource, $action, $method, true)->shouldBe($actionImpl2);
    }

    function it_should_find_action_for_any_resource(
        CollectionActionInterface $actionImpl1,
        InstanceActionInterface $actionImpl2
    ) {
        $resource = 'foo';
        $action = 'meta';
        $method = 'GET';

        $this->addCollectionAction($actionImpl1, '*', $action, $method);
        $this->addInstanceAction($actionImpl2, '*', $action, $method);

        $this->resolve($resource, $action, $method)->shouldBe($actionImpl1);
        $this->resolve($resource, $action, $method, true)->shouldBe($actionImpl2);
    }
}
