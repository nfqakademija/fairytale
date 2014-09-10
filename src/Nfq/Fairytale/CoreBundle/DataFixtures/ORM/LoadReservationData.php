<?php

namespace Nfq\Fairytale\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Nfq\Fairytale\CoreBundle\Entity\Book;
use Nfq\Fairytale\CoreBundle\Entity\Reservation;

class LoadReservationData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 5;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var EntityManager $em */
        $em = $manager;

        $example = new Reservation();
        $example->setUser($em->getPartialReference('Nfq\Fairytale\CoreBundle\Entity\User', 1));
        $example->setBook($em->getPartialReference('Nfq\Fairytale\CoreBundle\Entity\Book', 1));
        $example->setStatus('waiting');
        $example->setCreatedAt(new \DateTime());
        $manager->persist($example);

        $example = new Reservation();
        $example->setUser($em->getPartialReference('Nfq\Fairytale\CoreBundle\Entity\User', 2));
        $example->setBook($em->getPartialReference('Nfq\Fairytale\CoreBundle\Entity\Book', 1));
        $example->setStatus('returned');
        $example->setCreatedAt(new \DateTime());
        $manager->persist($example);


        $manager->flush();
    }
}
