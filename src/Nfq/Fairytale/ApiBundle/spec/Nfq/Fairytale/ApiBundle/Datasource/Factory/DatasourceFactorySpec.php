<?php

namespace spec\Nfq\Fairytale\ApiBundle\Datasource\Factory;

use Nfq\Fairytale\ApiBundle\Datasource\DataSourceInterface;
use Nfq\Fairytale\ApiBundle\Datasource\Factory\DatasourceFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin DatasourceFactory
 */
class DatasourceFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\Datasource\Factory\DatasourceFactory');
    }

    /**
     * Following test basically checks if methods are called properly
     */
    function it_should_create_parametrized_datasource(DataSourceInterface $dataSource)
    {
        $dataSource->setResource('foo')->willReturn($dataSource);
        $dataSource->getResource()->willReturn('foo');
        $this->setDatasource($dataSource);

        $this->create('foo')->getResource()->shouldBe('foo');
    }
}
