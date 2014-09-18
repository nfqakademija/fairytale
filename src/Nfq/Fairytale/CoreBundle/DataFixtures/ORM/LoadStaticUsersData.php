<?php

namespace Nfq\Fairytale\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nfq\Fairytale\CoreBundle\Entity\User;

class LoadStaticUsersData extends UserLoadingFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 10;
    }

    /**
     * @inheritdoc
     */
    function load(ObjectManager $manager)
    {
        parent::load($manager);

        $manager->persist($this->createUser('admin', 'John', 'Doe', ['ROLE_ADMIN']));
        $manager->persist($this->createUser('user', 'Jane', 'Doe', ['ROLE_USER']));
        /*
         * Disclaimer: ROLE_OWNER can't be set on user in normal every day use of user management,
         * however it is used here to save time pretending that every resource queried
         * by this user is "owned" from ApiBundle point of view.
         *
         * Some things might not work as intended using this user - it's expected
         */
        $manager->persist($this->createUser('owner', 'John', 'Smith', ['ROLE_OWNER']));

        $manager->flush();
    }

    /**
     * @param string   $username
     * @param string   $name
     * @param string   $lastname
     * @param string[] $roles
     * @return User
     */
    private function createUser($username, $name, $lastname, $roles)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setName($name);
        $user->setLastname($lastname);
        $user->setEmail($username . '@fairytale.dev');
        $user->setPassword($this->makePassword($user, 'secret'));
        $user->setRoles($roles);
        $user->setEnabled(true);
        $user->setImage($this->getImage('users'));

        return $user;
    }
}
