<?php

namespace Nfq\Fairytale\ApiBundle\Actions;

class ActionManager
{
    /** @var ActionInterface[] */
    protected $actions = [];

    /**
     * Attempts to find action that matches given request parameters
     *
     * @param string $resource
     * @param string $actionName
     * @param string $httpMethod
     *
     * @return ActionInterface|null
     */
    public function find($resource, $actionName, $httpMethod)
    {
        // TODO: "*" matches any resource
        $key = $this->buildKey($resource, $actionName, $httpMethod);
        if (isset($this->actions[$key])) {
            return $this->actions[$key];
        } else {
            return null;
        }
    }

    /**
     * @param string $resource
     * @param string $actionName
     * @param string $httpMethod
     *
     * @return string
     */
    private function buildKey($resource, $actionName, $httpMethod)
    {
        return sprintf("%s-%s-%s", $resource, $actionName, $httpMethod);
    }

    /**
     * @param ActionInterface $actionImpl
     * @param string          $resource
     * @param string          $actionName
     * @param string          $httpMethod
     * @return $this
     */
    public function addAction(ActionInterface $actionImpl, $resource, $actionName, $httpMethod)
    {
        $this->actions[$this->buildKey($resource, $actionName, $httpMethod)] = $actionImpl;

        return $this;
    }
}
