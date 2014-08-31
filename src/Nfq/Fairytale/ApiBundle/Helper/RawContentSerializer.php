<?php

namespace Nfq\Fairytale\ApiBundle\Helper;

use JMS\Serializer\SerializerInterface;
use Nfq\Fairytale\ApiBundle\Actions\ActionResult;

class RawContentSerializer
{
    /** @var  SerializerInterface */
    protected $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param ActionResult $actionResult
     * @return mixed
     */
    public function serialize(ActionResult $actionResult)
    {
        $serializeOne = function ($one) {
            return $this->serializer->deserialize($this->serializer->serialize($one, 'json'), 'array', 'json');
        };

        switch ($actionResult->getType()) {
            case ActionResult::INSTANCE:
                return $serializeOne($actionResult->getResult());
            case ActionResult::COLLECTION:
                return array_map($serializeOne, $actionResult->getResult());
            case ActionResult::SIMPLE:
                return $actionResult->getResult();
            default:
                throw new \InvalidArgumentException("Unsupported ActionResult type {$actionResult->getType()}");
        }
    }
}
