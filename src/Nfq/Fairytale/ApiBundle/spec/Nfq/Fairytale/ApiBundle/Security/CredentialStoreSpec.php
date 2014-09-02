<?php

namespace spec\Nfq\Fairytale\ApiBundle\Security;

use Nfq\Fairytale\ApiBundle\Actions\ActionInterface;
use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use Nfq\Fairytale\ApiBundle\DataSource\Factory\DataSourceFactory;
use Nfq\Fairytale\ApiBundle\Security\CredentialStore;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

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
                    'custom'                => [
                        'customField' => 'ROLE_USER',
                    ]
                ],
            ]
        ];

        $this->setAcl($acl);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\Security\CredentialStore');
    }

    function it_should_resolve_required_roles(ActionInterface $action)
    {
        $action->getName()->willReturn(CredentialStore::CREATE);

        $this->getRequiredRole('FooBundle:Bar', $action, 'id')->shouldBe('ROLE_USER');
        $this->getRequiredRole('FooBundle:Bar', $action, 'password')->shouldBe('ROLE_ADMIN');

        $this->getRequiredRole('FooBundle:Baz', $action, 'id')->shouldBe('ROLE_USER');
        $this->getRequiredRole('FooBundle:Baz', $action)->shouldBe(['id' => 'ROLE_USER']);
    }

    function it_should_throw_if_cant_resolve(ActionInterface $action)
    {
        $action->getName()->willReturn(CredentialStore::DELETE);

        $this->shouldThrow('Nfq\Fairytale\ApiBundle\Security\InvalidConfigurationException')
            ->during('getRequiredRole', ['FooBundle:Qux', $action, 'id']);

        $this->shouldThrow('Nfq\Fairytale\ApiBundle\Security\InvalidConfigurationException')
            ->during('getRequiredRole', ['FooBundle:Qux', $action]);
    }

    function it_should_resolve_accessible_fields_for_role(ActionInterface $action)
    {
        $action->getName()->willReturn(CredentialStore::CREATE);

        $roleHierarchy = new RoleHierarchy(['ROLE_ADMIN' => ['ROLE_USER']]);
        $this->setRoleHierarchy($roleHierarchy);

        $this->getAccessibleFields([new Role('ROLE_USER')], 'FooBundle:Bar', $action)->shouldBe(
            [
                'id' => 'ROLE_USER',
            ]
        );

        $this->getAccessibleFields([new Role('ROLE_ADMIN')], 'FooBundle:Bar', $action)->shouldBe(
            [
                'id'       => 'ROLE_USER',
                'password' => 'ROLE_ADMIN',
            ]
        );
    }

    function it_should_resolve_for_custom_actions(ActionInterface $action)
    {
        $action->getName()->willReturn('custom');

        $this->getRequiredRole('FooBundle:Qux', $action, 'customField')->shouldBe('ROLE_USER');
        $this->getRequiredRole('FooBundle:Qux', $action)->shouldBe(['customField' => 'ROLE_USER']);
    }

    function it_should_check_datasource_for_obejct_ownership(
        DataSourceFactory $factory,
        DataSourceInterface $dataSource
    ) {
        $object = (object)['foo' => 'bar'];
        $user = (object)['name' => 'bob'];
        $isOwned = true;

        $factory->create('stdClass')->willReturn($dataSource);
        $dataSource->isOwnedBy($object, $user)->willReturn($isOwned);

        $this->setDataSourceFactory($factory);
        $this->isOwnedBy($object, $user)->shouldBe($isOwned);
    }
}
