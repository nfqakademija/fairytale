<?php

namespace Nfq\Fairytale\ApiBundle\Helper;

class OwnershipResolver
{
    /** @var  string[] */
    private $ownerships;

    /**
     * @param string[] $ownerships
     */
    public function setOwnerships($ownerships)
    {
        $this->ownerships = $ownerships;
    }

    /**
     * @param string|integer $uid
     * @param object         $instance
     *
     * @returns bool
     */
    public function resolve($uid, $instance)
    {
        $class = get_class($instance);
        $uidField = @$this->ownerships[$class] ?: null;
        if ($uidField) {
            $ref = new \ReflectionObject($instance);
            $prop = $ref->getProperty($uidField);
            $prop->setAccessible(true);
            $value = $prop->getValue($instance);
            return $value == $uid;
        }
        return false;
    }
}
