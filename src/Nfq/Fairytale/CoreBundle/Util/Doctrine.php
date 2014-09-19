<?php

namespace Nfq\Fairytale\CoreBundle\Util;

use Doctrine\ORM\Proxy\Proxy;

class Doctrine
{
    public static function extractEntity($object)
    {
        if ($object instanceof Proxy) {
            if (!$object->__isInitialized()) {
                $object->__load();
            }
            $ref = new \ReflectionObject($object);
            $refEntityClass = $ref->getParentClass();
            $entity = $refEntityClass->newInstance();
            $props = $refEntityClass->getProperties();
            foreach ($props as $prop) {
                $prop->setAccessible(true);
                $prop->setValue($entity, $prop->getValue($object));
            }
            return $entity;
        } else {
            return $object;
        }
    }

    /**
     * @param $entity
     * @return array
     */
    public static function extractRaw($entity)
    {
        $ref = new \ReflectionObject($entity);
        $props = $ref->getProperties();

        $raw = [];
        foreach ($props as $prop) {
            $prop->setAccessible(true);
            $raw[$prop->getName()] = $prop->getValue($entity);
        }
        return $raw;
    }
}
