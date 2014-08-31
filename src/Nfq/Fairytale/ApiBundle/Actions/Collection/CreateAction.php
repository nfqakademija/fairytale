<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Collection;

use Nfq\Fairytale\ApiBundle\Actions\CollectionActionInterface;
use Nfq\Fairytale\ApiBundle\Actions\DataSourceFactoryAwareInterface;
use Nfq\Fairytale\ApiBundle\Actions\DataSourceFactoryAwareTrait;
use Symfony\Component\HttpFoundation\Request;

class CreateAction implements CollectionActionInterface, DataSourceFactoryAwareInterface
{
    use DataSourceFactoryAwareTrait;

    /**
     * @inheritdoc
     */
    public function execute(Request $request, $resource)
    {
        $instance = $this->factory->create($resource)->create($request->attributes->get('payload'));

        return [$instance, 201];
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'collection.create';
    }
}
