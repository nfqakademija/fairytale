<?php

namespace spec\Nfq\Fairytale\ApiBundle\Actions\Instance;

use Nfq\Fairytale\ApiBundle\Datasource\DataSourceInterface;
use Nfq\Fairytale\ApiBundle\Datasource\Factory\DatasourceFactory;
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

    function it_should_read_from_datasource(DatasourceFactory $factory, DataSourceInterface $dataSource)
    {
        $request = Request::create('/user/1', 'DELETE');
        $dataSource->delete(1)->willReturn(true);

        $factory->create('user')->willReturn($dataSource);

        $this->setFactory($factory);

        $this->execute($request, 'user', 1)->shouldBe([['status' => 'success'], 200]);
    }

    function it_should_throw_if_instance_not_found(DatasourceFactory $factory, DataSourceInterface $dataSource)
    {
        $request = Request::create('/user/1', 'DELETE');

        $dataSource->delete(1)->willReturn(false);
        $factory->create('user')->willReturn($dataSource);

        $this->setFactory($factory);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('execute', [$request, 'user', 1]);
    }
}
