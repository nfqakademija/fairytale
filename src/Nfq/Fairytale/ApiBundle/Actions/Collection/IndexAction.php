<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Collection;

use Nfq\Fairytale\ApiBundle\Actions\DataSourceFactoryAwareAction;
use Symfony\Component\HttpFoundation\Request;

class IndexAction extends DataSourceFactoryAwareAction implements CollectionActionInterface
{
    const NAME = 'collection.index';

    /**
     * @inheritdoc
     */
    public function execute(Request $request, $resource)
    {
        $instance = $this->factory->create($resource)->index(
            $request->query->get('limit'),
            $request->query->get('offset')
        );

        return [$instance, 200];
    }
}
