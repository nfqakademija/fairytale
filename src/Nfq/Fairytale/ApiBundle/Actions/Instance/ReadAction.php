<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Instance;

use Nfq\Fairytale\ApiBundle\Actions\DataSourceFactoryAwareAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReadAction extends DataSourceFactoryAwareAction implements InstanceActionInterface
{
    const NAME = 'instance.read';

    /**
     * @inheritdoc
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
