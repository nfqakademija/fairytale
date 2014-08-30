<?php

namespace spec\Nfq\Fairytale\ApiBundle\Helper;

use Nfq\Fairytale\ApiBundle\Helper\ResourceResolver;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin ResourceResolver
 */
class ResourceResolverSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\Helper\ResourceResolver');
    }

    function it_should_resolve_resource_to_entity_name()
    {
        $this->setResourceMapping(['foo' => 'BarBundle:Foo']);
        $this->resolve('foo')->shouldBe('BarBundle:Foo');
    }
}
