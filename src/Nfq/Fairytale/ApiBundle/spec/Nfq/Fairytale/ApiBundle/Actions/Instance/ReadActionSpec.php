<?php

namespace spec\Nfq\Fairytale\ApiBundle\Actions\Instance;

use Nfq\Fairytale\ApiBundle\Actions\ActionResult;
use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;

/**
 * @mixin \Nfq\Fairytale\ApiBundle\Actions\Instance\ReadAction
 */
class ReadActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\Actions\Instance\ReadAction');
    }

    function it_should_read_via_dataSource(DataSourceInterface $dataSource)
    {
        $obj = new \stdClass();

        $request = Request::create('/user/1', 'GET');
        $dataSource->read(1)->willReturn($obj);

        $result = $this->execute($request, $dataSource, 1);

        $result->shouldHaveType('Nfq\Fairytale\ApiBundle\Actions\ActionResult');
        $result->getStatusCode()->shouldBe(200);
        $result->getResult()->shouldBe($obj);
        $result->getType()->shouldBe(ActionResult::INSTANCE);
    }

    function it_should_throw_if_instance_not_found(DataSourceInterface $dataSource)
    {
        $request = Request::create('/user/1', 'GET');
        $dataSource->read(1)->willReturn(null);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('execute', [$request, $dataSource, 1]);
    }
}
