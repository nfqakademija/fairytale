<?php

namespace Nfq\Fairytale\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Nfq\Fairytale\CoreBundle\Entity\Comment;
use Nfq\Fairytale\CoreBundle\Entity\Reservation;

class LoadStaticCommentsData implements FixtureInterface, OrderedFixtureInterface
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
        $manager->persist($this->createComment($manager, 2, 1, '-1 hour', 'Foo bar'));
        $manager->flush();
    }

    /**
     * @param EntityManager $manager
     * @param               $userId
     * @param               $bookId
     * @param               $createdAt
     * @return Reservation
     */
    private function createComment(EntityManager $manager, $userId, $bookId, $createdAt, $content)
    {
        $comment = new Comment();
        $comment->setCreatedAt(new \DateTime($createdAt));
        $comment->setUser($manager->getPartialReference('Nfq\Fairytale\CoreBundle\Entity\User', $userId));
        $comment->setBook($manager->getPartialReference('Nfq\Fairytale\CoreBundle\Entity\Book', $bookId));
        $comment->setContent($content);

        return $comment;
    }
}
