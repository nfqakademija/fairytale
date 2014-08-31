<?php

namespace Nfq\Fairytale\ApiBundle\Actions\Instance;

use Nfq\Fairytale\ApiBundle\Actions\ActionResult;
use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteAction extends BaseInstanceAction
{
    const NAME = 'instance.delete';

    /**
     * @inheritdoc
     */
    public function execute(Request $request, DataSourceInterface $resource, $identifier)
    {
        if (!$resource->delete(intval($identifier))) {
            throw new NotFoundHttpException();
        }

        return ActionResult::simple(200, ['status' => 'success']);
    }
}
