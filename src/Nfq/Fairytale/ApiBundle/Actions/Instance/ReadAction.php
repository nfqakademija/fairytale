<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Instance;

use Nfq\Fairytale\ApiBundle\Actions\ActionResult;
use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReadAction extends BaseInstanceAction
{
    const NAME = 'instance.read';

    /**
     * @inheritdoc
     */
    public function execute(Request $request, DataSourceInterface $resource, $identifier)
    {
        $instance = $resource->read($identifier);
        if (is_null($instance)) {
            throw new NotFoundHttpException();
        }

        return ActionResult::instance(200, $instance);
    }
}
