<?php

namespace Nfq\Fairytale\ApiBundle\Helper;

class ResourceResolver
{
    /** @var  mixed */
    protected $resourceMapping;

    public function setResourceMapping($resourceMapping)
    {
        $this->resourceMapping = $resourceMapping;
    }

    /**
     * @param string $resource
     * @return string
     */
    public function resolve($resource)
    {
        if (array_key_exists($resource, $this->resourceMapping)) {
            return $this->resourceMapping[$resource];
        }
    }
}
