<?php

namespace Nfq\Fairytale\ApiBundle\Actions;

abstract class BaseAction implements ActionInterface
{
    const NAME = null;

    /**
     * @return string
     */
    public function getName()
    {
        return static::NAME;
    }
}
