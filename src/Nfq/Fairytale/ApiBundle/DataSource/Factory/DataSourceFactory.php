<?php

namespace Nfq\Fairytale\ApiBundle\DataSource\Factory;

use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;

class DataSourceFactory
{
    /** @var  DataSourceInterface */
    protected $dataSource;

    /** @var  mixed */
    protected $source;

    /**
     * @param DataSourceInterface $dataSource
     * @return void
     */
    public function setDataSource(DataSourceInterface $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * @param mixed $source
     * @return void
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * Creates parametrized instance of configured resource
     *
     * @param string $resource Resource identifier
     * @return DataSourceInterface
     */
    public function create($resource)
    {
        return $this->dataSource->setResource($resource);
    }
} 
