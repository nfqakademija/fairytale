<?php

namespace spec\Nfq\Fairytale\ApiBundle\Actions\Collection;

use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use Nfq\Fairytale\ApiBundle\DataSource\Factory\DataSourceFactory;
use Nfq\Fairytale\ApiBundle\EventListener\AuthorizationListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;

/**
 * @mixin \Nfq\Fairytale\ApiBundle\Actions\Collection\CreateAction
 */
class CreateActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\Actions\Collection\CreateAction');
    }

    function it_should_create_via_dataSource(DataSourceFactory $factory, DataSourceInterface $dataSource)
    {
        $data = [
            'name' => 'foo'
        ];
        $request = Request::create('/user', 'POST');
        $request->attributes->set(AuthorizationListener::API_REQUEST_PAYLOAD, $data);
        $dataSource->create($data)->willReturn(array_merge($data, ['id' => 1]));

        $factory->create('user')->willReturn($dataSource);

        $this->setFactory($factory);

        $this->execute($request, 'user', 1)->shouldBe([array_merge($data, ['id' => 1]), 201]);
    }
}
