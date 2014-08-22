<?php

namespace spec\Nfq\Fairytale\ApiBundle\Controller;

use JMS\Serializer\Serializer;
use Nfq\Fairytale\ApiBundle\Controller\ApiController;
use Nfq\Fairytale\ApiBundle\Datasource\DataSourceInterface;
use Nfq\Fairytale\ApiBundle\Datasource\Factory\DatasourceFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;

/**
 * @mixin ApiController
 */
class ApiControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\Controller\ApiController');
    }

    function it_can_read_resource(
        Request $request,
        DatasourceFactory $factory,
        DataSourceInterface $dataSource,
        Serializer $serializer
    ) {
        $fakeData = (object)['id' => 2, 'bar' => 'baz'];

        $serializer->serialize($fakeData, 'json')->willReturn(json_encode($fakeData));
        $dataSource->read(2)->willReturn($fakeData);
        $factory->create('NfqFairytaleCoreBundle:User')->willReturn($dataSource);

        $this->setSerializer($serializer);
        $this->setDatasourceFactory($factory);
        $this->setMapping(['user' => 'NfqFairytaleCoreBundle:User']);

        $response = $this->readAction($request, 'user', 2);

        $response->shouldHaveType('Symfony\Component\HttpFoundation\Response');
        $response->getContent()->shouldBe(json_encode($fakeData));
    }
}
