<?php

namespace Nfq\Fairytale\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nfq\Fairytale\CoreBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $this->setPassword($userAdmin, 'admin');
        $userAdmin->setEmail('admin@admin.com');
        $userAdmin->setName('The Admin');
        $userAdmin->setRoles(['ROLE_ADMIN']);

        $manager->persist($userAdmin);

        for($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername($faker->userName);
            $this->setPassword($user, 'secret');
            $user->setEmail($faker->companyEmail);
            $user->setName($faker->name);
            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);
        }
        $manager->flush();
    }

    private function setPassword(User $userAdmin, $password)
    {
        /** @var PasswordEncoderInterface $encoder */
        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($userAdmin);
        $userAdmin->setPassword($encoder->encodePassword($password, $userAdmin->getSalt()));
    }
}
