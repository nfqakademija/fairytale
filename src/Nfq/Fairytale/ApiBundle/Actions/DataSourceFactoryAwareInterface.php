<?php

namespace Nfq\Fairytale\ApiBundle\Actions;

use Nfq\Fairytale\ApiBundle\Datasource\Factory\DataSourceFactory;

interface DataSourceFactoryAwareInterface {

    public function setFactory(DataSourceFactory $factory);
} 
