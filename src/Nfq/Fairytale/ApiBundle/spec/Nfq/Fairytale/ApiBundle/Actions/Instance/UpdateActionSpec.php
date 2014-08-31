<?php

namespace spec\Nfq\Fairytale\ApiBundle\Actions\Instance;

use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use Nfq\Fairytale\ApiBundle\DataSource\Factory\DataSourceFactory;
use Nfq\Fairytale\ApiBundle\EventListener\AuthorizationListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;

/**
 * @mixin \Nfq\Fairytale\ApiBundle\Actions\Instance\UpdateAction
 */
class UpdateActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\Actions\Instance\UpdateAction');
    }

    function it_should_update_via_dataSource(DataSourceFactory $factory, DataSourceInterface $dataSource)
    {
        $full = [
            'id' => 1,
        ];
        $data = ['name' => 'foo'];
        $request = Request::create('/user/1', 'PUT');
        $request->attributes->set(AuthorizationListener::API_REQUEST_PAYLOAD, $data);
        $dataSource->update(1, $data)->willReturn(true);
        $dataSource->read(1)->willReturn(array_merge($full, $data));

        $factory->create('user')->willReturn($dataSource);

        $this->setFactory($factory);

        $this->execute($request, 'user', 1)->shouldBe([array_merge($full, $data), 200]);
    }

    function it_should_throw_if_instance_not_found(DataSourceFactory $factory, DataSourceInterface $dataSource)
    {
        $data = ['name' => 'foo'];
        $request = Request::create('/user/1', 'PUT');
        $request->attributes->set(AuthorizationListener::API_REQUEST_PAYLOAD, $data);
        $dataSource->update(1, $data)->willReturn(null);

        $factory->create('user')->willReturn($dataSource);

        $this->setFactory($factory);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('execute', [$request, 'user', 1]);
    }
}
