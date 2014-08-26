<?php

namespace Nfq\Fairytale\ApiBundle\Actions;

class ActionManager
{
    /** @var ResourceActionInterface[] */
    protected $actions = [];

    /**
     * Attempts to find action that matches given request parameters
     *
     * @param string $resource
     * @param string $actionName
     * @param string $httpMethod
     *
     * @return ResourceActionInterface|null
     */
    public function find($resource, $actionName, $httpMethod)
    {
        /*
         * Magic.
         * Attempts to grab action normally.
         * If it does not exist, attempts to grab action from '*'.
         * If it does not exist, returns null
         */
        return @$this->actions[$resource][$actionName][$httpMethod] ?: @$this->actions['*'][$actionName][$httpMethod];
    }

    /**
     * @param ResourceActionInterface $actionImpl
     * @param string                  $resource
     * @param string                  $actionName
     * @param string                  $httpMethod
     * @return $this
     */
    public function addResourceAction(ResourceActionInterface $actionImpl, $resource, $actionName, $httpMethod)
    {
        $this->actions[$resource][$actionName][$httpMethod] = $actionImpl;

        return $this;
    }
}
