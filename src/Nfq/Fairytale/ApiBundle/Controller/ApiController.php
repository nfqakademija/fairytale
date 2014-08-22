<?php

namespace Nfq\Fairytale\ApiBundle\Controller;

use JMS\Serializer\Serializer;
use Nfq\Fairytale\ApiBundle\Datasource\DataSourceInterface;
use Nfq\Fairytale\ApiBundle\Datasource\Factory\DatasourceFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        throw new HttpException(501);
    }

    public function indexAction(Request $request, $resource)
    {
        return new Response(
            $this->serializer->serialize($this->factory->create($this->mapping[$resource])->index(), 'json'),
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
