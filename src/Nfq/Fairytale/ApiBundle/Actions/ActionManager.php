<?php

namespace Nfq\Fairytale\ApiBundle\Actions;

use Nfq\Fairytale\ApiBundle\Actions\Collection\CollectionActionInterface;
use Nfq\Fairytale\ApiBundle\Actions\Instance\InstanceActionInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ActionManager
{
    /** @var array */
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
        if (isset($this->actions[$resource][$actionName][$httpMethod][$forInstance])) {
            return $this->actions[$resource][$actionName][$httpMethod][$forInstance];
        }

        $action = @$this->actions['*'][$actionName][$httpMethod][$forInstance];

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
