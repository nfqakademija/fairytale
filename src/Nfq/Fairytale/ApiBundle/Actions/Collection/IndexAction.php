<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Collection;

use Nfq\Fairytale\ApiBundle\Actions\ActionResult;
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
        return ActionResult::collection(
            200,
            $resource->index(
                $request->query->get('limit'),
                $request->query->get('offset')
            )
        );
    }
}
