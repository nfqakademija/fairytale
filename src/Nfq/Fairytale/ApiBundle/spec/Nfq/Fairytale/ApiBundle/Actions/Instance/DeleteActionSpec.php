<?php

namespace spec\Nfq\Fairytale\ApiBundle\Actions\Instance;

use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use Nfq\Fairytale\ApiBundle\DataSource\Factory\DataSourceFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;

/**
 * @mixin \Nfq\Fairytale\ApiBundle\Actions\Instance\DeleteAction
 */
class DeleteActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\Actions\Instance\DeleteAction');
    }

    function it_should_delete_via_dataSource(DataSourceFactory $factory, DataSourceInterface $dataSource)
    {
        $request = Request::create('/user/1', 'DELETE');
        $dataSource->delete(1)->willReturn(true);

        $factory->create('user')->willReturn($dataSource);

        $this->setFactory($factory);

        $this->execute($request, 'user', 1)->shouldBe([['status' => 'success'], 200]);
    }

    function it_should_throw_if_instance_not_found(DataSourceFactory $factory, DataSourceInterface $dataSource)
    {
        $request = Request::create('/user/1', 'DELETE');

        $dataSource->delete(1)->willReturn(false);
        $factory->create('user')->willReturn($dataSource);

        $this->setFactory($factory);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('execute', [$request, 'user', 1]);
    }
}