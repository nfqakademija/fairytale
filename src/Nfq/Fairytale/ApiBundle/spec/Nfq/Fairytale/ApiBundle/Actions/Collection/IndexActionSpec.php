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

    function it_should_index_via_dataSource(DataSourceInterface $dataSource)
    {
        $request = new Request(['limit' => 1, 'offset' => 1]);
        $dataSource->index(1, 1)->willReturn([]);

        $this->execute($request, $dataSource)->shouldBe([[], 200]);
    }
}
