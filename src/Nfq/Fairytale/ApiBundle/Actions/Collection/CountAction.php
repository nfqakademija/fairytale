<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Collection;

use Nfq\Fairytale\ApiBundle\Actions\BaseAction;
use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use Symfony\Component\HttpFoundation\Request;

class CountAction extends BaseAction implements CollectionActionInterface
{
    const NAME = 'collection.count';

    /**
     * @inheritdoc
     */
    public function execute(Request $request, DataSourceInterface $resource)
    {
        return [(object)['count' => $resource->count()], 200];
    }
}
