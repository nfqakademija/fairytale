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

    public function readAction($resource, $identifier)
    {
        $instance = $this->factory->create($this->resolver->resolve($resource))->read($identifier);

        if (!$instance) {

            throw new NotFoundHttpException();
        } else {

            return [$instance, 200];
        }
    }

    public function createAction(Request $request, $resource)
    {
        return [
            $this->factory->create($this->resolver->resolve($resource))->create(
                $request->attributes->get('payload')
            ),
            201
        ];
    }

    public function updateAction(Request $request, $resource, $identifier)
    {
        return [
            $this->factory->create($this->resolver->resolve($resource))->update(
                $identifier,
                $request->attributes->get('payload')
            ),
            200
        ];
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

    public function deleteAction($resource, $identifier)
    {
        $deleted = $this->factory
            ->create($this->resolver->resolve($resource))
            ->delete($identifier);

        return [
            ['status' => $deleted ? 'success' : 'failed'],
            $deleted ? 200 : 400
        ];
    }

    public function customAction(Request $request, $resource, $actionName, $identifier = null)
    {
        $action = $this->actionManager->find($resource, $actionName, $request->getMethod(), !is_null($identifier));

        switch (true) {
            case ($action instanceof CollectionActionInterface):
                return $action->execute($request, $this->resolver->resolve($resource));
            case ($action instanceof InstanceActionInterface):
                return $action->execute($request, $this->resolver->resolve($resource), $identifier);
            default:
                throw new BadRequestHttpException(
                    sprintf("Action '%s' is not supported", $actionName)
                );
        }
    }
}
