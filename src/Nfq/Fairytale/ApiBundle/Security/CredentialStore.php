<?php

namespace Nfq\Fairytale\ApiBundle\Security;

use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;

class CredentialStore
{
    const CREATE = 'CREATE';
    const READ = 'READ';
    const UPDATE = 'UPDATE';
    const DELETE = 'DELETE';

    /** @var  array */
    protected $acl;
    /** @var  string */
    protected $defaultCredential;
    /** @var  RoleHierarchyInterface */
    protected $roleHierarchy;

    /**
     * @param array $acl
     */
    public function setAcl($acl)
    {
        $this->acl = $acl;
    }

    /**
     * @param RoleHierarchyInterface $roleHierarchy
     */
    public function setRoleHierarchy(RoleHierarchyInterface $roleHierarchy)
    {
        $this->roleHierarchy = $roleHierarchy;
    }

    /**
     * Returns minimal required role level to access given resource
     *
     * @param      $resource
     * @param      $field
     * @param null $action
     * @return mixed
     */
    public function getRequiredRole($resource, $action = null, $field = null)
    {
        list($bundle, $entity) = explode(':', $resource);

        switch (true) {
            case ($field):
                return @$this->acl[$bundle][$entity][$action][$field] ?: $this->defaultCredential;

            case ($action):
                return @$this->acl[$bundle][$entity][$action] ?: $this->defaultCredential;

            default:
                return @$this->acl[$bundle][$entity] ?: $this->defaultCredential;
        }
    }

    /**
     * @param RoleInterface[] $roles
     * @param string          $resource
     * @param string          $action
     * @return array
     */
    public function getAccesibleFields(array $roles, $resource, $action)
    {
        $reachableRoles = array_unique(
            array_map(
                function (Role $role) {
                    return $role->getRole();
                },
                $this->roleHierarchy->getReachableRoles($roles)
            )
        );

        $acl = $this->getRequiredRole($resource, $action);

        $fields = array_filter(
            $acl,
            function ($requiredRole) use ($reachableRoles) {
                return in_array($requiredRole, $reachableRoles);
            }
        );

        return $fields;
    }

    /**
     * @param string $defaultCredential
     */
    public function setDefaultCredential($defaultCredential)
    {
        $this->defaultCredential = $defaultCredential;
    }
}
