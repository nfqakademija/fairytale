<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Instance;

use Nfq\Fairytale\ApiBundle\Actions\DataSourceFactoryAwareInterface;
use Nfq\Fairytale\ApiBundle\Actions\DataSourceFactoryAwareTrait;
use Nfq\Fairytale\ApiBundle\Actions\InstanceActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateAction implements InstanceActionInterface, DataSourceFactoryAwareInterface
{
    use DataSourceFactoryAwareTrait;

    /**
     * @inheritdoc
     */
    public function execute(Request $request, $resource, $identifier)
    {
        $instance = $this->factory->create($resource)->update($identifier, $request->attributes->get('payload'));
        if (is_null($instance)) {
            throw new NotFoundHttpException();
        }
        return [$instance, 200];
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'instance.update';
    }
}
