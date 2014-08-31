<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Instance;

use Nfq\Fairytale\ApiBundle\Actions\BaseAction;
use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReadAction extends BaseAction implements InstanceActionInterface
{
    const NAME = 'instance.read';

    /**
     * @inheritdoc
     */
    public function execute(Request $request, DataSourceInterface $resource, $identifier)
    {
        $instance = $resource->read($identifier);
        if (is_null($instance)) {
            throw new NotFoundHttpException();
        }
        return [$instance, 200];
    }
}
