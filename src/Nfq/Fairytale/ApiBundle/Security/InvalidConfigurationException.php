<?php

namespace Nfq\Fairytale\ApiBundle\Security;

class InvalidConfigurationException extends \InvalidArgumentException
{
    /**
     * @param string $bundle
     * @param string $entity
     * @param string $actionName
     */
    public function __construct($bundle, $entity, $actionName)
    {
        parent::__construct(
            sprintf("ACL for %s:%s:%s was not found.\nDid you forget to add it?", $bundle, $entity, $actionName)
        );
    }
}
