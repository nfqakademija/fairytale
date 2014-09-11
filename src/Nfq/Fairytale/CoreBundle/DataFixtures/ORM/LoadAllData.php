<?php

namespace Nfq\Fairytale\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Faker\ORM\Doctrine\Populator;
use Nfq\Fairytale\CoreBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class LoadAllData implements FixtureInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected function returnValue($value)
    {
        return function () use ($value) {
            return $value;
        };
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {

        $generator = Factory::create();
        $populator = new Populator($generator, $manager);
        $this->populateUsers($populator, $generator);
        $this->populateAuthors($populator, $generator);
        $this->populateBooks($populator, $generator);
        $this->populateCategories($populator, $generator);
        $this->populateReservations($populator, $generator);
        $this->populateRatings($populator, $generator);
        $this->populateComments($populator, $generator);
        $populator->execute();
    }

    /**
     * @param User   $user
     * @param string $password
     * @return string
     */
    private function makePassword(User $user, $password)
    {
        /** @var PasswordEncoderInterface $encoder */
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        return $encoder->encodePassword($password, $user->getSalt());
    }

    /**
     * @param Populator $populator
     * @param Generator $generator
     */
    private function populateUsers(Populator $populator, Generator $generator)
    {
        $populator->addEntity(
            '\Nfq\Fairytale\CoreBundle\Entity\User',
            95,
            [
                'email'               => function () use ($generator) {
                    return $generator->unique()->companyEmail;
                },
                'username'               => function () use ($generator) {
                    return $generator->unique()->username;
                },
                'enabled'             => $this->returnValue(true),
                'lastLogin'           => $this->returnValue(null),
                'locked'              => $this->returnValue(false),
                'expired'             => $this->returnValue(false),
                'expiresAt'           => $this->returnValue(null),
                'confirmationToken'   => $this->returnValue(null),
                'passwordRequestedAt' => $this->returnValue(null),
                'roles'               => function () use ($generator) {
                    return $generator->randomElement(['ROLE_USER', 'ROLE_USER', 'ROLE_USER', 'ROLE_ADMIN']);
                },
                'credentialsExpired'  => $this->returnValue(false),
                'credentialsExpireAt' => $this->returnValue(null),
                'salt'                => function () use ($generator) {
                    return $generator->md5;
                },
            ],
            [
                function (User $user) {
                    $user->setPassword($this->makePassword($user, 'secret'));
                },
            ]
        );
    }

    /**
     * @param Populator $populator
     * @param Generator $generator
     */
    private function populateAuthors(Populator $populator, Generator $generator)
    {
        $populator->addEntity(
            '\Nfq\Fairytale\CoreBundle\Entity\Author',
            70,
            [
                'name' => function () use ($generator) {
                    return $generator->unique()->name;
                },
            ]
        );
    }

    /**
     * @param Populator $populator
     * @param Generator $generator
     */
    private function populateBooks(Populator $populator, Generator $generator)
    {
        $populator->addEntity(
            '\Nfq\Fairytale\CoreBundle\Entity\Book',
            100,
            [
                'title'       => function () use ($generator) {
                    return $generator->sentence();
                },
                'description' => function () use ($generator) {
                    return $generator->realText();
                },
                'summary'     => function () use ($generator) {
                    return $generator->realText();
                },
            ]
        );
    }

    /**
     * @param Populator $populator
     * @param Generator $generator
     */
    private function populateCategories(Populator $populator, Generator $generator)
    {
        $populator->addEntity(
            '\Nfq\Fairytale\CoreBundle\Entity\Category',
            4,
            [
                'title' => function () use ($generator) {
                    return $generator->unique()->randomElement(
                        ['Technical', 'Design', 'Business', 'Project Management']
                    );
                },
            ]
        );
    }

    /**
     * @param Populator $populator
     * @param Generator $generator
     */
    private function populateReservations(Populator $populator, Generator $generator)
    {
        $populator->addEntity(
            '\Nfq\Fairytale\CoreBundle\Entity\Reservation',
            50,
            [
                'createdAt' => function () use ($generator) {
                    return $generator->dateTimeThisYear;
                },
                'status'    => function () use ($generator) {
                    return $generator->randomElement(['waiting', 'reading', 'returned']);
                },
            ]
        );
    }

    /**
     * @param Populator $populator
     * @param Generator $generator
     */
    private function populateRatings(Populator $populator, Generator $generator)
    {
        $populator->addEntity(
            '\Nfq\Fairytale\CoreBundle\Entity\Rating',
            300,
            [
                'created' => function () use ($generator) {
                    return $generator->dateTimeThisYear;
                },
                'value'   => function () use ($generator) {
                    return $generator->numberBetween(0, 5);
                },
            ]
        );
    }

    /**
     * @param Populator $populator
     * @param Generator $generator
     */
    private function populateComments(Populator $populator, Generator $generator)
    {
        $populator->addEntity(
            '\Nfq\Fairytale\CoreBundle\Entity\Comment',
            150,
            [
                'created' => function () use ($generator) {
                    return $generator->dateTimeThisYear;
                },
                'content' => function () use ($generator) {
                    return $generator->realText();
                },
            ]
        );
    }
}
