<?php

namespace Nfq\Fairytale\ApiBundle\DataSource;

class ClassFactory
{
    /**
     * @param string $className
     * @return object
     */
    public function create($className)
    {
        return new $className;
    }
}
