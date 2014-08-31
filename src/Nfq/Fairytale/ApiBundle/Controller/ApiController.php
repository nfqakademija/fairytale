<?php

namespace Nfq\Fairytale\ApiBundle\Controller;

use Nfq\Fairytale\ApiBundle\Actions\ActionManager;
use Nfq\Fairytale\ApiBundle\Actions\CollectionActionInterface;
use Nfq\Fairytale\ApiBundle\Actions\InstanceActionInterface;
use Nfq\Fairytale\ApiBundle\Datasource\Factory\DatasourceFactory;
use Nfq\Fairytale\ApiBundle\Helper\ResourceResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
