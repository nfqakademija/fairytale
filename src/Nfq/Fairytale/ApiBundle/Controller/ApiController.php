<?php

namespace Nfq\Fairytale\ApiBundle\Controller;

use Nfq\Fairytale\ApiBundle\Actions\ActionManager;
use Nfq\Fairytale\ApiBundle\Actions\CollectionActionInterface;
use Nfq\Fairytale\ApiBundle\Actions\InstanceActionInterface;
use Nfq\Fairytale\ApiBundle\Datasource\Factory\DatasourceFactory;
use Nfq\Fairytale\ApiBundle\Helper\ResourceResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiController implements ApiControllerInterface
{
    /** @var  DatasourceFactory */
    protected $factory;

    /** @var  int */
    protected $defaultIndexSize;

    /** @var  ActionManager */
    protected $actionManager;

    /** @var  ResourceResolver */
    protected $resolver;

    /**
     * @param DatasourceFactory $factory
     */
    public function setDatasourceFactory(DatasourceFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param int $defaultCollectionSize
     */
    public function setDefaultIndexSize($defaultCollectionSize)
    {
        $this->defaultIndexSize = $defaultCollectionSize;
    }

    /**
     * @param ActionManager $actionManager
     */
    public function setActionManager(ActionManager $actionManager)
    {
        $this->actionManager = $actionManager;
    }

    /**
     * @param ResourceResolver $resolver
     */
    public function setResolver(ResourceResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function indexAction(Request $request, $resource)
    {
        return [
            $this->factory->create($this->resolver->resolve($resource))->index(
                $request->query->get('limit', $this->defaultIndexSize),
                $request->query->get('offset', 0)
            ),
            200
        ];
    }

    public function customAction(Request $request, $resource, $action, $identifier = null)
    {
        switch (true) {
            case ($action instanceof CollectionActionInterface):
                return $action->execute($request, $resource);
            case ($action instanceof InstanceActionInterface):
                return $action->execute($request, $resource, $identifier);
        }
    }
}
