<?php

namespace Nfq\Fairytale\ApiBundle\Controller;

use Nfq\Fairytale\ApiBundle\Actions\ActionManager;
use Nfq\Fairytale\ApiBundle\Datasource\Factory\DatasourceFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiController implements ApiControllerInterface
{
    /** @var array */
    protected $mapping = [];

    /** @var  DatasourceFactory */
    protected $factory;

    /** @var  int */
    protected $defaultIndexSize;

    /** @var  ActionManager */
    protected $actionManager;

    /**
     * @param DatasourceFactory $factory
     */
    public function setDatasourceFactory(DatasourceFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param array $mapping
     */
    public function setMapping($mapping)
    {
        $this->mapping = $mapping;
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

    public function readAction($resource, $identifier)
    {
        $instance = $this->factory->create($this->mapping[$resource])->read($identifier);

        if (!$instance) {

            throw new NotFoundHttpException();
        } else {

            return [$instance, 200];
        }
    }

    public function createAction(Request $request, $resource)
    {
        return [
            $this->factory->create($this->mapping[$resource])->create(
                json_decode($request->getContent(), true)
            ),
            201
        ];
    }

    public function updateAction(Request $request, $resource, $identifier)
    {
        return [
            $this->factory->create($this->mapping[$resource])->update(
                $identifier,
                json_decode($request->getContent(), true)
            ),
            200
        ];
    }

    public function indexAction(Request $request, $resource)
    {
        return [
            $this->factory->create($this->mapping[$resource])->index(
                $request->query->get('limit', $this->defaultIndexSize),
                $request->query->get('offset', 0)
            ),
            200
        ];
    }

    public function deleteAction($resource, $identifier)
    {
        $deleted = $this->factory
            ->create($this->mapping[$resource])
            ->delete($identifier);

        return [
            ['status' => $deleted ? 'success' : 'failed'],
            $deleted ? 200 : 400
        ];
    }

    public function customAction(Request $request, $resource, $actionName)
    {
        if ($action = $this->actionManager->find($resource, $actionName, $request->getMethod())) {

            return $action->execute($request, $this->mapping[$resource]);
        } else {

            throw new BadRequestHttpException(
                sprintf("Resource '%s' does not support '%s' action", $resource, $actionName)
            );
        }
    }
}
