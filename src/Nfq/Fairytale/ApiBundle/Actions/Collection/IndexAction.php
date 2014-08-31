<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Collection;

use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use Symfony\Component\HttpFoundation\Request;

class IndexAction extends BaseCollectionAction
{
    const NAME = 'collection.index';

    /**
     * @inheritdoc
     */
    public function execute(Request $request, DataSourceInterface $resource)
    {
        $instance = $resource->index(
            $request->query->get('limit'),
            $request->query->get('offset')
        );

        return [$instance, 200];
    }
}
