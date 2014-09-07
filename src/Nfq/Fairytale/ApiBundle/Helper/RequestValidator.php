<?php

namespace Nfq\Fairytale\ApiBundle\Helper;

use Nfq\Fairytale\ApiBundle\Security\PermissionManager;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RequestValidator
{
    /** @var  PermissionManager */
    private $permissions;

    /**
     * @param PermissionManager $permissions
     */
    public function setPermissions(PermissionManager $permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * @param string     $resource
     * @param string     $actionName
     * @param array|null $payload
     * @param string[]   $roles
     *
     * @throws AccessDeniedHttpException
     */
    public function validate($resource, $actionName, $payload, $roles)
    {
        if (!$this->permissions->isReadable($resource, $actionName, $roles)) {
            throw new AccessDeniedHttpException('You are not allowed to access this resource');
        }

        if ($payload) {
            if (!$this->permissions->isWritable($resource, $actionName, $roles)) {
                throw new AccessDeniedHttpException('You are not allowed to access this resource');
            }

            foreach ($payload as $field => $value) {
                if (!$this->permissions->isWritable($resource . ':' . $field, $actionName, $roles)) {
                    throw new AccessDeniedHttpException('You are not allowed to access this resource');
                }
            }
        }
    }

}
