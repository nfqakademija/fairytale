<?php

namespace Nfq\Fairytale\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nfq\Fairytale\CoreBundle\Entity\Author;

class LoadAuthorData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $authors = [
            'Ellen Lupton',
            'Abbott Miller',
            'Judith Wilde',
            'Richard Wilde',
            'Donald A. Norman',
            'Josef Müller-Brockmann',
            'Bill Moggridge',
            'John Dewey',
            'Paul Rand',
            'Graphics Artists Guild'
        ];

        foreach ($authors as $name) {
            $author = new Author();
            $author->setName($name);
            $manager->persist($author);

            $this->addReference('author-' . $name, $author);
        }

        $manager->flush();
    }
}