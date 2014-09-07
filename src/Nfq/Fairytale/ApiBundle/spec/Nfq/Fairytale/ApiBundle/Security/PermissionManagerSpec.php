<?php

namespace spec\Nfq\Fairytale\ApiBundle\Security;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin \Nfq\Fairytale\ApiBundle\Security\PermissionManager
 */
class PermissionManagerSpec extends ObjectBehavior
{
    function let()
    {
        $acl = [
            'Foo\\BarBundle\\Entity' => [
                'Baz' => [
                    'custom.action' => [
                        'read'  => [
                            'id'    => 'ROLE_USER',
                            'name'  => 'ROLE_USER',
                            'email' => 'ROLE_OWNER',
                        ],
                        'write' => [
                            'id'    => 'NOT A ROLE',
                            'name'  => 'ROLE_ADMIN',
                            'email' => 'ROLE_OWNER',
                        ],
                    ],
                ],
                'Qux' => [
                    'custom.action' => [
                        'read'  => 'ROLE_USER',
                        'write' => 'ROLE_ADMIN',
                    ],
                ],
            ],
        ];
        $this->setAcl($acl);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\Security\PermissionManager');
    }

    function it_should_decide_if_token_can_read_field()
    {
        $cases = [
            // by field, when fields are defined separately
            ['Foo\\BarBundle\\Entity\\Baz:id', 'custom.action', 'does not exist', false],
            ['Foo\\BarBundle\\Entity\\Baz:id', 'custom.action', 'ROLE_ANONYMOUS', false],
            ['Foo\\BarBundle\\Entity\\Baz:id', 'custom.action', 'ROLE_USER', true],
            ['Foo\\BarBundle\\Entity\\Baz:id', 'custom.action', 'ROLE_OWNER', true],
            ['Foo\\BarBundle\\Entity\\Baz:id', 'custom.action', 'ROLE_ADMIN', true],
            // by entity, when only entity is defiend
            ['Foo\\BarBundle\\Entity\\Qux', 'custom.action', 'does not exist', false],
            ['Foo\\BarBundle\\Entity\\Qux', 'custom.action', 'ROLE_ANONYMOUS', false],
            ['Foo\\BarBundle\\Entity\\Qux', 'custom.action', 'ROLE_USER', true],
            ['Foo\\BarBundle\\Entity\\Qux', 'custom.action', 'ROLE_OWNER', true],
            ['Foo\\BarBundle\\Entity\\Qux', 'custom.action', 'ROLE_ADMIN', true],
            // by entity, when fields are defined
            ['Foo\\BarBundle\\Entity\\Baz', 'custom.action', 'does not exist', false],
            ['Foo\\BarBundle\\Entity\\Baz', 'custom.action', 'ROLE_ANONYMOUS', false],
            ['Foo\\BarBundle\\Entity\\Baz', 'custom.action', 'ROLE_USER', true],
            ['Foo\\BarBundle\\Entity\\Baz', 'custom.action', 'ROLE_OWNER', true],
            ['Foo\\BarBundle\\Entity\\Baz', 'custom.action', 'ROLE_ADMIN', true],
            // by unknown entity
            ['Foo\\BarBundle\\Entity\\Quux', 'custom.action', 'ROLE_ADMIN', false],
            ['Foo\\BarBundle\\Entity\\Quux:id', 'unknown.action', 'ROLE_ADMIN', false],
            ['Foo\\UnknownBundle\\Entity\\Quux:id', 'custom.action', 'ROLE_ADMIN', false],
        ];

        foreach ($cases as $case) {
            $this->isReadable($case[0], $case[1], $this->getAccesibleRoles($case[2]))->shouldBe($case[3]);
        }
    }

    function it_should_decide_if_token_can_write_field()
    {
        $cases = [
            // by field, when fields are defined separately
            ['Foo\\BarBundle\\Entity\\Baz:id', 'custom.action', 'does not exist', false],
            ['Foo\\BarBundle\\Entity\\Baz:id', 'custom.action', 'ROLE_ANONYMOUS', false],
            ['Foo\\BarBundle\\Entity\\Baz:id', 'custom.action', 'ROLE_USER', false],
            ['Foo\\BarBundle\\Entity\\Baz:id', 'custom.action', 'ROLE_OWNER', false],
            ['Foo\\BarBundle\\Entity\\Baz:id', 'custom.action', 'ROLE_ADMIN', false],
            // by entity, when only entity is defiend
            ['Foo\\BarBundle\\Entity\\Qux', 'custom.action', 'does not exist', false],
            ['Foo\\BarBundle\\Entity\\Qux', 'custom.action', 'ROLE_ANONYMOUS', false],
            ['Foo\\BarBundle\\Entity\\Qux', 'custom.action', 'ROLE_USER', false],
            ['Foo\\BarBundle\\Entity\\Qux', 'custom.action', 'ROLE_OWNER', false],
            ['Foo\\BarBundle\\Entity\\Qux', 'custom.action', 'ROLE_ADMIN', true],
            // by entity, when fields are defined
            ['Foo\\BarBundle\\Entity\\Baz', 'custom.action', 'does not exist', false],
            ['Foo\\BarBundle\\Entity\\Baz', 'custom.action', 'ROLE_ANONYMOUS', false],
            ['Foo\\BarBundle\\Entity\\Baz', 'custom.action', 'ROLE_USER', false],
            ['Foo\\BarBundle\\Entity\\Baz', 'custom.action', 'ROLE_OWNER', true],
            ['Foo\\BarBundle\\Entity\\Baz', 'custom.action', 'ROLE_ADMIN', true],
            // by unknown entity
            ['Foo\\BarBundle\\Entity\\Quux', 'custom.action', 'ROLE_ADMIN', false],
            ['Foo\\BarBundle\\Entity\\Quux:id', 'unknown.action', 'ROLE_ADMIN', false],
            ['Foo\\UnknownBundle\\Entity\\Quux:id', 'custom.action', 'ROLE_ADMIN', false],
        ];

        foreach ($cases as $case) {
            $this->isWritable($case[0], $case[1], $this->getAccesibleRoles($case[2]))->shouldBe($case[3]);
        }
    }

    private function getAccesibleRoles($role)
    {
        $roles = ['ROLE_ANONYMOUS', 'ROLE_USER', 'ROLE_OWNER', 'ROLE_ADMIN', 'DISABLED'];

        $out = [];

        if (in_array($role, $roles)) {
            foreach ($roles as $r) {
                $out[] = $r;
                if ($r === $role) {
                    return $out;
                }
            }
        }

        return $out;
    }
}
