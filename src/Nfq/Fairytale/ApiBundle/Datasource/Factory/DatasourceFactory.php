<?php

namespace Nfq\Fairytale\ApiBundle\Datasource\Factory;

use Nfq\Fairytale\ApiBundle\Datasource\DataSource;

class DatasourceFactory
{
    /** @var  DataSource */
    protected $datasource;

    /** @var  mixed */
    protected $source;

    /**
     * @param DataSource $datasource
     */
    public function setDatasource(DataSource $datasource)
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
