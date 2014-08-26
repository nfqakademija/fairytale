<?php

namespace Nfq\Fairytale\ApiBundle\Actions;

class ActionManager
{
    /** @var CollectionActionInterface[] */
    protected $actions = [];

    /**
     * Attempts to find action that matches given request parameters
     *
     * @param string $resource
     * @param string $actionName
     * @param string $httpMethod
     * @param bool   $forInstance
     *
     * @return CollectionActionInterface|InstanceActionInterface|null
     */
    public function find($resource, $actionName, $httpMethod, $forInstance = false)
    {
        /*
         * Magic.
         * Attempts to grab action normally.
         * If it does not exist, attempts to grab action from '*'.
         * If it does not exist, returns null
         */
        return @$this->actions[$resource][$actionName][$httpMethod][$forInstance]
            ?: @$this->actions['*'][$actionName][$httpMethod][$forInstance];
    }

    /**
     * @param CollectionActionInterface $actionImpl
     * @param string                    $resource
     * @param string                    $actionName
     * @param string                    $httpMethod
     * @return $this
     */
    public function addCollectionAction(CollectionActionInterface $actionImpl, $resource, $actionName, $httpMethod)
    {
        $this->actions[$resource][$actionName][$httpMethod][false] = $actionImpl;

        return $this;
    }

    public function addInstanceAction(InstanceActionInterface $actionImpl, $resource, $actionName, $httpMethod)
    {
        $this->actions[$resource][$actionName][$httpMethod][true] = $actionImpl;

        return $this;
    }
}
