<?php

namespace Nfq\Fairytale\ApiBundle\Controller;

use JMS\Serializer\Serializer;
use Nfq\Fairytale\ApiBundle\Datasource\DataSourceInterface;
use Nfq\Fairytale\ApiBundle\Datasource\Factory\DatasourceFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController implements ApiControllerInterface
{
    /** @var array */
    protected $mapping = [];

    /** @var  Serializer */
    protected $serializer;

    /** @var  DatasourceFactory */
    protected $factory;

    /**
     * @param DataSourceInterface $datasource
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

    public function readAction(Request $request, $resource, $identifier)
    {
        // TODO: serialize(???, $request->getRequestFormat());

        return new Response(
            $this->serializer->serialize(
                $this->factory
                    ->create($this->mapping[$resource])
                    ->read($identifier),
                'json'
            ),
            200,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }

    public function createAction(Request $request, $resource)
    {
        return new Response();
    }

    public function updateAction(Request $request, $resource, $id)
    {
        return new Response();
    }

    public function indexAction(Request $request, $resource)
    {
        return new Response();
    }

    public function deleteAction(Request $request, $resource, $id)
    {
        return new Response();
    }
}
