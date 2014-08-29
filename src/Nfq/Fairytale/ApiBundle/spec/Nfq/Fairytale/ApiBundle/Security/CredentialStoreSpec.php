<?php

namespace spec\Nfq\Fairytale\ApiBundle\Security;

use Nfq\Fairytale\ApiBundle\Security\CredentialStore;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin CredentialStore
 */
class CredentialStoreSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\Security\CredentialStore');
    }

    function it_should_resolve_credentials_for_actions()
    {
        $acl = [
            'FooBundle' => [
                'Bar' => [
                    'id'       => 'ROLE_USER',
                    'password' => 'ROLE_ADMIN'
                ],
                'Baz' => [
                    'id' => [
                        'CREATE' => 'ROLE_USER',
                    ]
                ],
                'Qux' => [
                    'id' => [
                        'CREATE' => 'ROLE_USER',
                    ]
                ],
            ]
        ];

        $this->setAcl($acl);
        $this->getRequiredRole('FooBundle:Bar', 'id')->shouldBe('ROLE_USER');
        $this->getRequiredRole('FooBundle:Bar', 'password')->shouldBe('ROLE_ADMIN');

        $this->getRequiredRole('FooBundle:Baz', 'id', CredentialStore::CREATE)->shouldBe('ROLE_USER');
    }

    function it_should_resolve_to_default_credentials_if_undefined()
    {
        $acl = [
            'FooBundle' => [
                'Bar' => [
                ],
                'Baz' => [
                    'id' => []
                ],
            ]
        ];
        $default = 'ROLE_ADMIN';

        $this->setAcl($acl);
        $this->setDefaultCredential($default);
        $this->getRequiredRole('FooBundle:Bar', 'id')->shouldBe('ROLE_ADMIN');
        $this->getRequiredRole('FooBundle:Baz', 'id', CredentialStore::CREATE)->shouldBe('ROLE_ADMIN');
    }
}
