<?php

namespace Nfq\Fairytale\ApiBundle\Actions;

use Nfq\Fairytale\ApiBundle\DataSource\Factory\DataSourceFactory;

trait DataSourceFactoryAwareTrait
{
    /** @var  DataSourceFactory */
    protected $factory;

    public function setFactory(DataSourceFactory $factory)
    {
        $this->factory = $factory;
    }
}
