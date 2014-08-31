<?php

namespace spec\Nfq\Fairytale\ApiBundle\DataSource\Factory;

use Nfq\Fairytale\ApiBundle\Datasource\DataSourceInterface;
use Nfq\Fairytale\ApiBundle\Datasource\Factory\DataSourceFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin DataSourceFactory
 */
class DataSourceFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\DataSource\Factory\DataSourceFactory');
    }

    function it_should_create_parametrized_data_source(DataSourceInterface $dataSource)
    {
        $dataSource->setResource('foo')->willReturn($dataSource);
        $dataSource->getResource()->willReturn('foo');
        $this->setDataSource($dataSource);

        $this->create('foo')->getResource()->shouldBe('foo');
    }
}
