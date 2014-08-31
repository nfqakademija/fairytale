<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Collection;

use Nfq\Fairytale\ApiBundle\Actions\CollectionActionInterface;
use Nfq\Fairytale\ApiBundle\Actions\DataSourceFactoryAwareInterface;
use Nfq\Fairytale\ApiBundle\Actions\DataSourceFactoryAwareTrait;
use Symfony\Component\HttpFoundation\Request;

class CountAction implements CollectionActionInterface, DataSourceFactoryAwareInterface
{
    use DataSourceFactoryAwareTrait;

    /**
     * @inheritdoc
     */
    public function execute(Request $request, $resource)
    {
        return [(object)['count' => $this->factory->create($resource)->count()], 200];
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'collection.count';
    }
}
