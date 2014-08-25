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
     * @param ResourceActionInterface $actionImpl
     * @param string          $resource
     * @param string          $actionName
     * @param string          $httpMethod
     * @return $this
     */
    public function addResourceAction(ResourceActionInterface $actionImpl, $resource, $actionName, $httpMethod)
    {
        $this->actions[$this->buildKey($resource, $actionName, $httpMethod)] = $actionImpl;

        return $this;
    }
}
