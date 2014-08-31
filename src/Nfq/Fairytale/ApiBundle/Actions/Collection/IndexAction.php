<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Collection;

use Nfq\Fairytale\ApiBundle\Actions\CollectionActionInterface;
use Nfq\Fairytale\ApiBundle\Actions\DataSourceFactoryAwareInterface;
use Nfq\Fairytale\ApiBundle\Actions\DataSourceFactoryAwareTrait;
use Symfony\Component\HttpFoundation\Request;

class IndexAction implements CollectionActionInterface, DataSourceFactoryAwareInterface
{
    use DataSourceFactoryAwareTrait;

    /**
     * @inheritdoc
     */
    public function execute(Request $request, $resource)
    {
        $instance = $this->factory->create($resource)->index();

        return [$instance, 200];
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'collection.index';
    }
}
