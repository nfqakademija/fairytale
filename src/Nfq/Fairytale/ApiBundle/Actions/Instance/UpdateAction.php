<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Instance;

use Nfq\Fairytale\ApiBundle\Actions\ActionResult;
use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use Nfq\Fairytale\ApiBundle\EventListener\AuthorizationListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateAction extends BaseInstanceAction
{
    const NAME = 'instance.update';

    /**
     * @inheritdoc
     */
    public function execute(Request $request, DataSourceInterface $resource, $identifier)
    {
        $instance = $resource->update(
            $identifier,
            $request->attributes->get(AuthorizationListener::API_REQUEST_PAYLOAD)
        );

        if (is_null($instance)) {
            throw new NotFoundHttpException();
        }

        return ActionResult::instance(200, $resource->read($identifier));
    }
}
