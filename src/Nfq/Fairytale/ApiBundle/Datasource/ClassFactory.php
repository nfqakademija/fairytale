<?php

namespace Nfq\Fairytale\ApiBundle\Datasource;

class ClassFactory
{
    public function create($className)
    {
        return new $className;
    }
}
