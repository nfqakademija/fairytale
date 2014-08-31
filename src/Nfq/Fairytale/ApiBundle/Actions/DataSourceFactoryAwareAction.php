<?php

namespace Nfq\Fairytale\ApiBundle\Actions;

abstract class DataSourceFactoryAwareAction implements ActionInterface, DataSourceFactoryAwareInterface
{
    use DataSourceFactoryAwareTrait;

    const NAME = null;

    /**
     * @return string
     */
    public function getName()
    {
       return self::NAME;
    }
}
