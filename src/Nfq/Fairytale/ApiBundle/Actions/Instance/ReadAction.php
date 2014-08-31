<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Instance;

use Nfq\Fairytale\ApiBundle\Actions\DataSourceFactoryAwareInterface;
use Nfq\Fairytale\ApiBundle\Actions\DataSourceFactoryAwareTrait;
use Nfq\Fairytale\ApiBundle\Actions\InstanceActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReadAction implements InstanceActionInterface, DataSourceFactoryAwareInterface
{
    use DataSourceFactoryAwareTrait;

    /**
     * Performs the action
     *
     * @param Request $request
     * @param string  $resource
     * @param string  $identifier
     * @return Response
     */
    public function execute(Request $request, $resource, $identifier)
    {
        $instance = $this->factory->create($resource)->read($identifier);
        if (is_null($instance)) {
            throw new NotFoundHttpException();
        }
        return [$instance, 200];
    }
}
