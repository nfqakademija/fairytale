<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Instance;

use Nfq\Fairytale\ApiBundle\Actions\DataSourceFactoryAwareInterface;
use Nfq\Fairytale\ApiBundle\Actions\DataSourceFactoryAwareTrait;
use Nfq\Fairytale\ApiBundle\Actions\InstanceActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteAction implements InstanceActionInterface, DataSourceFactoryAwareInterface
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
        if (!$this->factory->create($resource)->delete(intval($identifier))) {
            throw new NotFoundHttpException();
        }
        return [['status' => 'success'], 200];
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'instance.delete';
    }
}
