<?php

namespace Nfq\Fairytale\CoreBundle\Actions\Category;

use Nfq\Fairytale\ApiBundle\Actions\ActionResult;
use Nfq\Fairytale\ApiBundle\Actions\Instance\BaseInstanceAction;
use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use Nfq\Fairytale\ApiBundle\DataSource\Factory\DataSourceFactory;
use Nfq\Fairytale\CoreBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetBooks extends BaseInstanceAction
{
    const NAME = 'category.books';

    /** @var  DataSourceFactory */
    protected $factory;

    /**
     * Performs the action
     *
     * @param Request             $request
     * @param DataSourceInterface $resource
     * @param string              $identifier
     * @return ActionResult
     */
    public function execute(Request $request, DataSourceInterface $resource, $identifier)
    {
        /** @var Category $cat */
        $cat = $resource->read($identifier);
        if (!$cat) {
            throw new NotFoundHttpException();
        }

        return ActionResult::collection(200, $cat->getBooks()->toArray());
    }

    /**
     * @param DataSourceFactory $factory
     */
    public function setFactory(DataSourceFactory $factory)
    {
        $this->factory = $factory;
    }
}
