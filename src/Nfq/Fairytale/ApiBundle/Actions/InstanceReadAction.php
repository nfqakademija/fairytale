<?php

namespace Nfq\Fairytale\ApiBundle\Actions;

use Nfq\Fairytale\ApiBundle\Datasource\Factory\DatasourceFactory;
use Symfony\Component\HttpFoundation\Request;

class InstanceReadAction implements InstanceActionInterface
{
    /** @var  DatasourceFactory */
    protected $factory;

    /**
     * @param DatasourceFactory $factory
     */
    public function setFactory(DatasourceFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @inheritdoc
     */
    public function execute(Request $request, $resource, $identifier)
    {
        return [
            $this->factory->create($resource)->read($identifier) ?: [],
            200
        ];
    }
}
