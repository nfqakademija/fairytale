<?php

namespace Nfq\Fairytale\ApiBundle\Datasource\Factory;

use Nfq\Fairytale\ApiBundle\Datasource\DataSourceInterface;

class DatasourceFactory
{
    /** @var  DataSourceInterface */
    protected $datasource;

    /** @var  mixed */
    protected $source;

    /**
     * @param DataSourceInterface $datasource
     */
    public function setDatasource(DataSourceInterface $datasource)
    {
        $this->datasource = $datasource;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @inheritdoc
     */
    public function create($resource)
    {
        return $this->datasource->setResource($resource);
    }
} 
