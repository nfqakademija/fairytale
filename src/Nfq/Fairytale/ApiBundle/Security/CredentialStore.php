<?php

namespace Nfq\Fairytale\ApiBundle\Security;

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

    /**
     * @param array $acl
     */
    public function setAcl($acl)
    {
        $this->acl = $acl;
    }

    /**
     * Returns minimal required role level to access given resource
     *
     * @param      $resource
     * @param      $field
     * @param null $action
     * @return mixed
     */
    public function getRequiredRole($resource, $field, $action = null)
    {
        list($bundle, $entity) = explode(':', $resource);

        switch (true) {
            case ($action):
                return @$this->acl[$bundle][$entity][$field][$action] ?: $this->defaultCredential;

            default:
                return @$this->acl[$bundle][$entity][$field] ?: $this->defaultCredential;
        }
    }

    /**
     * @param string $defaultCredential
     */
    public function setDefaultCredential($defaultCredential)
    {
        $this->defaultCredential = $defaultCredential;
    }
}
