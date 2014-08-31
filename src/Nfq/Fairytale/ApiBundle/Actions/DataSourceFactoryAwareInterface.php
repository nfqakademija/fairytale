<?php

namespace Nfq\Fairytale\ApiBundle\Actions;

use Nfq\Fairytale\ApiBundle\Datasource\Factory\DatasourceFactory;

interface DataSourceFactoryAwareInterface {

    public function setFactory(DatasourceFactory $factory);
} 
