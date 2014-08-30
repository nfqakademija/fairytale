<?php

namespace spec\Nfq\Fairytale\ApiBundle\Security;

use Nfq\Fairytale\ApiBundle\Security\CredentialStore;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

/**
 * @mixin CredentialStore
 */
class CredentialStoreSpec extends ObjectBehavior
{
    function let()
    {
        $acl = [
            'FooBundle' => [
                'Bar' => [
                    CredentialStore::CREATE => [
                        'id'       => 'ROLE_USER',
                        'password' => 'ROLE_ADMIN'
                    ],
                ],
                'Baz' => [
                    CredentialStore::CREATE => [
                        'id' => 'ROLE_USER',
                    ],
                ],
                'Qux' => [
                    CredentialStore::CREATE => [
                        'id' => 'ROLE_USER',
                    ],
                ],
            ]
        ];

        $this->setAcl($acl);
        $this->setDefaultCredential('ROLE_ADMIN');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\Security\CredentialStore');
    }

    function it_should_resolve_required_roles()
    {
        $this->getRequiredRole('FooBundle:Bar', CredentialStore::CREATE, 'id')->shouldBe('ROLE_USER');
        $this->getRequiredRole('FooBundle:Bar', CredentialStore::CREATE, 'password')->shouldBe('ROLE_ADMIN');

        $this->getRequiredRole('FooBundle:Baz', CredentialStore::CREATE, 'id')->shouldBe('ROLE_USER');
        $this->getRequiredRole('FooBundle:Baz', CredentialStore::CREATE)->shouldBe(['id' => 'ROLE_USER']);
    }

    function it_should_resolve_to_default_credentials_if_undefined()
    {
        $this->getRequiredRole('FooBundle:Qux', CredentialStore::DELETE, 'id')->shouldBe('ROLE_ADMIN');
        $this->getRequiredRole('FooBundle:Qux', CredentialStore::DELETE)->shouldBe('ROLE_ADMIN');
        $this->getRequiredRole('FooBundle:Qux')->shouldBe([CredentialStore::CREATE => ['id' => 'ROLE_USER']]);
    }

    function it_should_resolve_accesible_fields_for_role()
    {
        $roleHierarchy = new RoleHierarchy(['ROLE_ADMIN' => ['ROLE_USER']]);
        $this->setRoleHierarchy($roleHierarchy);

        $this->getAccesibleFields([new Role('ROLE_USER')], 'FooBundle:Bar', CredentialStore::CREATE)->shouldBe(
            [
                'id' => 'ROLE_USER',
            ]
        );

        $this->getAccesibleFields([new Role('ROLE_ADMIN')], 'FooBundle:Bar', CredentialStore::CREATE)->shouldBe(
            [
                'id'       => 'ROLE_USER',
                'password' => 'ROLE_ADMIN',
            ]
        );
    }
}
