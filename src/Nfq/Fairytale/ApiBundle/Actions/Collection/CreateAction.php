<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Collection;

use Nfq\Fairytale\ApiBundle\Actions\ActionResult;
use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use Nfq\Fairytale\ApiBundle\EventListener\AuthorizationListener;
use Symfony\Component\HttpFoundation\Request;

class CreateAction extends BaseCollectionAction
{
    const NAME = 'collection.create';

    /**
     * @inheritdoc
     */
    public function execute(Request $request, DataSourceInterface $resource)
    {
        return ActionResult::instance(
            201,
            $resource->create($request->attributes->get(AuthorizationListener::API_REQUEST_PAYLOAD))
        );
    }
}
