<?php

namespace spec\Nfq\Fairytale\ApiBundle\Actions\Instance;

use Nfq\Fairytale\ApiBundle\Actions\ActionResult;
use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
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

    function it_should_delete_via_dataSource(DataSourceInterface $dataSource)
    {
        $request = Request::create('/user/1', 'DELETE');
        $dataSource->delete(1)->willReturn(true);

        $result = $this->execute($request, $dataSource, 1);

        $result->shouldHaveType('Nfq\Fairytale\ApiBundle\Actions\ActionResult');
        $result->getStatusCode()->shouldBe(200);
        $result->getResult()->shouldBe(['status' => 'success']);
        $result->getType()->shouldBe(ActionResult::SIMPLE);
    }

    function it_should_throw_if_instance_not_found(DataSourceInterface $dataSource)
    {
        $request = Request::create('/user/1', 'DELETE');

        $dataSource->delete(1)->willReturn(false);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('execute', [$request, $dataSource, 1]);
    }
}
