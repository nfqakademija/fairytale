<?php

namespace spec\Nfq\Fairytale\ApiBundle\Actions\Collection;

use Nfq\Fairytale\ApiBundle\Datasource\DataSourceInterface;
use Nfq\Fairytale\ApiBundle\Datasource\Factory\DatasourceFactory;
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

    function it_should_create_via_datasource(DatasourceFactory $factory, DataSourceInterface $dataSource)
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
