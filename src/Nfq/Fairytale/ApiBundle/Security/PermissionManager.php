<?php

namespace Nfq\Fairytale\ApiBundle\Security;

class PermissionManager
{
    /** @var  array */
    protected $acl;

    /**
     * @param array $acl
     */
    public function setAcl($acl)
    {
        $this->acl = $acl;
    }

    /**
     * @param string $path
     * @param string $action
     * @param array  $roles
     * @return bool
     */
    public function isReadable($path, $action, array $roles)
    {
        return $this->isAllowed($path, $action, $roles, 'read');
    }

    /**
     * @param string $path
     * @param string $action
     * @param array  $roles
     * @return bool
     */
    public function isWritable($path, $action, array $roles)
    {
        return $this->isAllowed($path, $action, $roles, 'write');
    }

    /**
     * @param string $path
     * @param string $action
     * @param array  $roles
     * @param string $operation
     * @return bool
     */
    private function isAllowed($path, $action, array $roles, $operation)
    {
        $hasField = false !== strpos($path, ':');
        list($fqcn, $field) = $hasField ? explode(':', $path) : [$path, null];
        $parts = explode('\\', $fqcn);
        $class = array_pop($parts);
        $namespace = join('\\', $parts);

        if ($hasField) {
            $requiredRole = @$this->acl[$namespace][$class][$action][$operation][$field];
        } else {
            $requiredRole = @$this->acl[$namespace][$class][$action][$operation];
        }

        return [] != array_intersect(is_array($requiredRole) ? $requiredRole : [$requiredRole], $roles);
    }
}
