<?php

namespace Nfq\Fairytale\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Nfq\Fairytale\CoreBundle\Entity\Reservation;
use Nfq\Fairytale\CoreBundle\Entity\User;

class LoadStaticReservationsData implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 30;
    }

    /**
     * @inheritdoc
     */
    function load(ObjectManager $manager)
    {
        $manager->persist($this->createReservation($manager, 2, 1, '-1 month', '-10 minutes', '-3 minutes'));
        $manager->persist($this->createReservation($manager, 2, 2, '-1 month', '-2 minutes', null));
        $manager->flush();
    }

    /**
     * @param EntityManager $manager
     * @param               $userId
     * @param               $bookId
     * @param               $createdAt
     * @param               $takenAt
     * @param               $returnedAt
     * @return Reservation
     */
    private function createReservation(EntityManager $manager, $userId, $bookId, $createdAt, $takenAt, $returnedAt)
    {
        $reservation = new Reservation();
        $reservation->setCreatedAt(new \DateTime($createdAt));
        $reservation->setTakenAt($takenAt ? new \DateTime($takenAt) : null);
        $reservation->setReturnedAt($returnedAt ? new \DateTime($returnedAt) : null);
        $reservation->setUser($manager->getPartialReference('Nfq\Fairytale\CoreBundle\Entity\User', $userId));
        $reservation->setBook($manager->getPartialReference('Nfq\Fairytale\CoreBundle\Entity\Book', $bookId));

        return $reservation;
    }
}
