<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Collection;

use Nfq\Fairytale\ApiBundle\Actions\CollectionActionInterface;
use Nfq\Fairytale\ApiBundle\Actions\DataSourceFactoryAwareAction;
use Symfony\Component\HttpFoundation\Request;

class CountAction extends DataSourceFactoryAwareAction implements CollectionActionInterface
{
    const NAME = 'collection.count';

    /**
     * @inheritdoc
     */
    public function execute(Request $request, $resource)
    {
        return [(object)['count' => $this->factory->create($resource)->count()], 200];
    }
}
