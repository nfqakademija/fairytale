<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Collection;

use Nfq\Fairytale\ApiBundle\Actions\CollectionActionInterface;
use Nfq\Fairytale\ApiBundle\Actions\DataSourceFactoryAwareAction;
use Symfony\Component\HttpFoundation\Request;

class CreateAction extends DataSourceFactoryAwareAction implements CollectionActionInterface
{
    const NAME = 'collection.create';

    /**
     * @inheritdoc
     */
    public function execute(Request $request, $resource)
    {
        $instance = $this->factory->create($resource)->create($request->attributes->get('payload'));

        return [$instance, 201];
    }
}
