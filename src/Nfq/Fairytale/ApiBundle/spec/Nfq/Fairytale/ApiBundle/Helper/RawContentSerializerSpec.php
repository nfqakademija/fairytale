<?php

namespace spec\Nfq\Fairytale\ApiBundle\Helper;

use JMS\Serializer\Serializer;
use Nfq\Fairytale\ApiBundle\Actions\ActionResult;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin \Nfq\Fairytale\ApiBundle\Helper\RawContentSerializer
 */
class RawContentSerializerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\Helper\RawContentSerializer');
    }

    function it_should_serialize_instance(ActionResult $actionResult, Serializer $serializer)
    {
        $payload = ['foo' => 'bar', 'baz' => 'qux'];
        $actionResult->getType()->willReturn(ActionResult::INSTANCE);
        $actionResult->getResult()->willReturn($payload);

        $serializedPayload = uniqid();
        $serializer->serialize($payload, 'json')->willReturn($serializedPayload);
        $serializer->deserialize($serializedPayload, 'array', 'json')->willReturn($payload);

        $this->setSerializer($serializer);
        $this->serialize($actionResult)->shouldBe($payload);
    }

    function it_should_serialize_collection(ActionResult $actionResult, Serializer $serializer)
    {
        $payload = [
            ['foo' => '1', 'baz' => '1'],
            ['foo' => '2', 'baz' => '2'],
        ];
        $actionResult->getType()->willReturn(ActionResult::COLLECTION);
        $actionResult->getResult()->willReturn($payload);

        foreach ($payload as $item) {
            $serializer->serialize($item, 'json')->willReturn(serialize($item));
            $serializer->deserialize(serialize($item), 'array', 'json')->willReturn($item);
        }

        $this->setSerializer($serializer);
        $this->serialize($actionResult)->shouldBe($payload);
    }

    function it_should_serialize_simple(ActionResult $actionResult)
    {
        $payload = ['foo' => 'bar', 'baz' => 'qux'];

        $actionResult->getType()->willReturn(ActionResult::SIMPLE);
        $actionResult->getResult()->willReturn($payload);

        /*
         * Doesn't even use serializer
         */
        $this->serialize($actionResult)->shouldBe($payload);
    }
}
