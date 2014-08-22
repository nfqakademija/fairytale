<?php

namespace Nfq\Fairytale\ApiBundle\Controller;

use JMS\Serializer\Serializer;
use Nfq\Fairytale\ApiBundle\Datasource\Factory\DatasourceFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiController implements ApiControllerInterface
{
    /** @var array */
    protected $mapping = [];

    /** @var  Serializer */
    protected $serializer;

    /** @var  DatasourceFactory */
    protected $factory;

    /** @var  int */
    protected $defaultIndexSize;

    /**
     * @param DatasourceFactory $factory
     */
    public function setDatasourceFactory(DatasourceFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Serializer $serializer
     */
    public function setSerializer(Serializer $serializer)
    {
        $this->serializer = $serializer;
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

    public function readAction(Request $request, $resource, $identifier)
    {
        // TODO: serialize(???, $request->getRequestFormat());
        $instance = $this->factory
            ->create($this->mapping[$resource])
            ->read($identifier);

        if (!$instance) {

            throw new NotFoundHttpException();
        } else {

            return new Response(
                $this->serializer->serialize($instance, 'json'),
                200,
                ['Content-Type' => 'application/json']
            );
        }
    }

    public function createAction(Request $request, $resource)
    {
        return new Response(
            $this->serializer->serialize(
                $this->factory
                    ->create($this->mapping[$resource])
                    ->create(json_decode($request->getContent(), true)),
                'json'
            ),
            201,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }

    public function updateAction(Request $request, $resource, $identifier)
    {
        return new Response(
            $this->serializer->serialize(
                $this->factory
                    ->create($this->mapping[$resource])
                    ->update($identifier, json_decode($request->getContent(), true)),
                'json'
            ),
            200,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }

    public function indexAction(Request $request, $resource)
    {
        return new Response(
            $this->serializer->serialize(
                $this->factory->create($this->mapping[$resource])->index(
                    $request->query->get('limit', $this->defaultIndexSize),
                    $request->query->get('offset', 0)
                ),
                'json'
            ),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    public function deleteAction(Request $request, $resource, $identifier)
    {
        $deleted = $this->factory
            ->create($this->mapping[$resource])
            ->delete($identifier);

        return new Response(
            $this->serializer->serialize(
                ['status' => $deleted ? 'success' : 'failed'],
                'json'
            ),
            $deleted ? 200 : 400,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }
}
