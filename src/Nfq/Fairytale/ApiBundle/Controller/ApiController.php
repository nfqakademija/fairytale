<?php

namespace Nfq\Fairytale\ApiBundle\Controller;

use Nfq\Fairytale\ApiBundle\Actions\Collection\CollectionActionInterface;
use Nfq\Fairytale\ApiBundle\Actions\Instance\InstanceActionInterface;
use Nfq\Fairytale\ApiBundle\DataSource\Factory\DataSourceFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller implements ApiControllerInterface
{
    public function customAction(Request $request, $resource, $action, $identifier = null)
    {
        /** @var DataSourceFactory $factory */
        $factory = $this->container->get('nfq_fairytale.data_source.factory');
        switch (true) {
            case ($action instanceof CollectionActionInterface):
                return $action->execute($request, $factory->create($resource));

            case ($action instanceof InstanceActionInterface):
                return $action->execute($request, $factory->create($resource), $identifier);
        }
    }
}
