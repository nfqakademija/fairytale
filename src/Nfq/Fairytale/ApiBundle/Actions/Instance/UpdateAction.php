<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Instance;

use Nfq\Fairytale\ApiBundle\Actions\BaseAction;
use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateAction extends BaseAction implements InstanceActionInterface
{
    const NAME = 'instance.update';

    /**
     * @inheritdoc
     */
    public function execute(Request $request, DataSourceInterface $resource, $identifier)
    {
        $instance = $resource->update($identifier, $request->attributes->get('payload'));
        if (is_null($instance)) {
            throw new NotFoundHttpException();
        }
        return [$resource->read($identifier), 200];
    }
}
