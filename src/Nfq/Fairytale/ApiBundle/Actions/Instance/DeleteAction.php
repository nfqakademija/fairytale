<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Instance;

use Nfq\Fairytale\ApiBundle\Actions\DataSourceFactoryAwareAction;
use Nfq\Fairytale\ApiBundle\Actions\InstanceActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteAction extends DataSourceFactoryAwareAction implements InstanceActionInterface
{
    const NAME = 'instance.delete';

    /**
     * @inheritdoc
     */
    public function execute(Request $request, $resource, $identifier)
    {
        if (!$this->factory->create($resource)->delete(intval($identifier))) {
            throw new NotFoundHttpException();
        }
        return [['status' => 'success'], 200];
    }
}
