<?php

namespace Nfq\Fairytale\ApiBundle\Actions;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ActionManager
{
    /** @var CollectionActionInterface[] */
    protected $actions = [
        '*' => [],
    ];

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
    public function resolve($resource, $actionName, $httpMethod, $forInstance = false)
    {
        $resource = array_key_exists($resource, $this->actions) ? $this->actions[$resource] : $this->actions['*'];

        $action = @$resource[$actionName ?: ''][$httpMethod][$forInstance];

        if (null === $action) {
            throw new BadRequestHttpException();
        }

        return $action;
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
