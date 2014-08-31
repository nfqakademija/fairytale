<?php

namespace Nfq\Fairytale\ApiBundle\Security;

use Nfq\Fairytale\ApiBundle\Actions\ActionInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;

class CredentialStore
{
    const CREATE = 'collection.create';
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
     * @param string          $resource
     * @param ActionInterface $action
     * @param string          $field
     *
     * @return mixed
     */
    public function getRequiredRole($resource, ActionInterface $action, $field = null)
    {
        list($bundle, $entity) = explode(':', $resource);

        if (array_key_exists($bundle, $this->acl)) {
            if (array_key_exists($entity, $this->acl[$bundle])) {
                if (array_key_exists($action->getName(), $this->acl[$bundle][$entity])) {
                    if ($field) {
                        return $this->acl[$bundle][$entity][$action->getName()][$field];
                    } else {
                        return $this->acl[$bundle][$entity][$action->getName()];
                    }
                }
            }
        }

        throw new InvalidConfigurationException($bundle, $entity, $action->getName());
    }

    /**
     * @param RoleInterface[] $roles
     * @param string          $resource
     * @param ActionInterface $action
     * @return array
     */
    public function getAccessibleFields(array $roles, $resource, $action)
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
