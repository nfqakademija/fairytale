<?php

namespace spec\Nfq\Fairytale\ApiBundle\Actions\Collection;

use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use Nfq\Fairytale\ApiBundle\DataSource\Factory\DataSourceFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;

/**
 * @mixin \Nfq\Fairytale\ApiBundle\Actions\Collection\IndexAction
 */
class IndexActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\Actions\Collection\IndexAction');
    }

    function it_should_index_via_dataSource(DataSourceFactory $factory, DataSourceInterface $dataSource)
    {
        $request = Request::create('/user');
        $dataSource->index()->willReturn([]);

        $factory->create('user')->willReturn($dataSource);

        $this->setFactory($factory);

        $this->execute($request, 'user')->shouldBe([[], 200]);
    }
}
