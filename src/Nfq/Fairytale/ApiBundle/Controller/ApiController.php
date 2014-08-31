<?php

namespace Nfq\Fairytale\ApiBundle\Controller;

use Nfq\Fairytale\ApiBundle\Actions\Collection\CollectionActionInterface;
use Nfq\Fairytale\ApiBundle\Actions\Instance\InstanceActionInterface;
use Symfony\Component\HttpFoundation\Request;

class ApiController implements ApiControllerInterface
{
    public function customAction(Request $request, $resource, $action, $identifier = null)
    {
        switch (true) {
            case ($action instanceof CollectionActionInterface):
                return $action->execute($request, $resource);
            case ($action instanceof InstanceActionInterface):
                return $action->execute($request, $resource, $identifier);
        }
    }
}
