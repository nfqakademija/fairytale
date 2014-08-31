<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Collection;

use Nfq\Fairytale\ApiBundle\Actions\BaseAction;
use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use Symfony\Component\HttpFoundation\Request;

class CreateAction extends BaseAction implements CollectionActionInterface
{
    const NAME = 'collection.create';

    /**
     * @inheritdoc
     */
    public function execute(Request $request, DataSourceInterface $resource)
    {
        $instance = $resource->create($request->attributes->get('payload'));

        return [$instance, 201];
    }
}
