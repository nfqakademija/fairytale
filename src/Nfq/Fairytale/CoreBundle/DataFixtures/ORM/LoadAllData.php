<?php

namespace Nfq\Fairytale\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Faker\ORM\Doctrine\Populator;
use Nfq\Fairytale\CoreBundle\Entity\Author;
use Nfq\Fairytale\CoreBundle\Entity\Book;
use Nfq\Fairytale\CoreBundle\Entity\Category;
use Nfq\Fairytale\CoreBundle\Entity\Reservation;
use Nfq\Fairytale\CoreBundle\Entity\User;

class LoadAllData extends UserLoadingFixture implements FixtureInterface, OrderedFixtureInterface
{

    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 20;
    }

    private function returnValue($value)
    {
        return function () use ($value) {
            return $value;
        };
    }

    /**
     * @inheritdoc
     */
    function load(ObjectManager $manager)
    {
        parent::load($manager);

        $generator = Factory::create();
        $populator = new Populator($generator, $manager);
        $this->populateUsers($populator, $generator);
        $this->populateAuthors($populator, $generator);
        $this->populateCategories($populator, $generator);
        $this->populateBooks($populator, $generator);
        $this->populateReservations($populator, $generator);
        $this->populateRatings($populator, $generator);
        $this->populateComments($populator, $generator);
        $populator->execute();
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
                'username'            => function () use ($generator) {
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
                    return $generator->randomElement([[], [], [], ['ROLE_ADMIN']]);
                },
                'credentialsExpired'  => $this->returnValue(false),
                'credentialsExpireAt' => $this->returnValue(null),
                'name'                => function () use ($generator) {
                    return $generator->firstName;
                },
                'lastname'            => function () use ($generator) {
                    return $generator->lastName;
                },
                'salt'                => function () use ($generator) {
                    return $generator->md5;
                },
                'image'               => function () {
                    return $this->getImage('users');
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
                'createdAt'   => function () use ($generator) {
                    return $generator->dateTimeThisYear;
                },
                'title'       => function () use ($generator) {
                    return $generator->sentence();
                },
                'description' => function () use ($generator) {
                    return $generator->realText();
                },
                'pages'       => function () use ($generator) {
                    return $generator->numberBetween(50, 500);
                },
                'publisher'   => function () use ($generator) {
                    return $generator->company;
                },
                'language'    => function () use ($generator) {
                    return $generator->languageCode;
                },
                'image'       => function () {
                    return $this->getImage('books');
                },
                'isbn'        => function () use ($generator) {
                    return $generator->ean13;
                },
                'cover'       => function () use ($generator) {
                    return $generator->randomElement(['soft', 'hard']);
                },
            ],
            [
                function (Book $book, $insertedEntities) use ($generator) {
                    /** @var Category $cat */
                    $cat = $generator->randomElement($insertedEntities['Nfq\Fairytale\CoreBundle\Entity\Category']);
                    $book->addCategory($cat);
                    $cat->addBook($book);

                    /** @var Author $author */
                    $author = $generator->randomElement($insertedEntities['Nfq\Fairytale\CoreBundle\Entity\Author']);
                    $book->addAuthor($author);
                    $author->addBook($book);
                }
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
            500,
            [
                'takenAt'    => function () use ($generator) {
                    return null;
                },
                'returnedAt' => function () use ($generator) {
                    return null;
                },
            ],
            [
                function (Reservation $reservation) use ($generator) {
                    $reservation->setCreatedAt($generator->dateTimeBetween('-1 year', '-1 month'));
                    if ($generator->boolean(33)) {
                        $reservation->setTakenAt($generator->dateTimeBetween('-1 month', '-1 week'));
                        if ($generator->boolean(66)) {
                            $reservation->setReturnedAt($generator->dateTimeBetween('-1 week', 'now'));
                        }
                    }
                }
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
                'createdAt' => function () use ($generator) {
                    return $generator->dateTimeThisYear;
                },
                'value'     => function () use ($generator) {
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
                'createdAt' => function () use ($generator) {
                    return $generator->dateTimeThisYear;
                },
                'content'   => function () use ($generator) {
                    return $generator->realText();
                },
            ]
        );
    }
}
