<?php

namespace Nfq\Fairytale\ApiBundle\Actions;

use Nfq\Fairytale\ApiBundle\Datasource\Factory\DatasourceFactory;

trait DataSourceFactoryAwareTrait
{
    /** @var  DataSourceFactory */
    protected $factory;

    public function setFactory(DatasourceFactory $factory)
    {
        $this->factory = $factory;
    }
}
