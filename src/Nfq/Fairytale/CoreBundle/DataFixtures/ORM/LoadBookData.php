<?php

namespace Nfq\Fairytale\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nfq\Fairytale\CoreBundle\Entity\Book;

class LoadBookData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $books = [
            [
                "title"       => "The ABC's of Bauhaus. The Bauhaus and Design Theory",
                "description" => "A thin but powerful book collecting essays of type and design based on the " .
                    "Bauhaus principles and " .
                    "theories edited by (and with contributions from) the indomitable husband and wife team of Ellen " .
                    "Lupton and Abbott Miller. A perfect primer for learning all about The Bauhaus.",
                "summary"     => "Paperback: 64 pages<br>Publisher: Princeton Architectural Press (June 15, 2000)<br>" .
                    "Language: English<br>ISBN-10: 1878271423<br>ISBN-13: 978-1878271426",
                "authors"     => ["author-Ellen Lupton", "author-Abbott Miller"],
                "categories"  => ["category-Design"]
            ],
        ];

        foreach ($books as $book) {
            $example = new Book();
            $example->setTitle($book["title"]);
            $example->setDescription($book["description"]);
            $example->setSummary($book["summary"]);

            foreach ($book['authors'] as $authorId) {
                $example->addAuthor($this->getReference($authorId));
            }

            foreach ($book['categories'] as $categoryId) {
                $example->addCategory($this->getReference($categoryId));
            }

            $manager->persist($example);
        }

        $manager->flush();
    }
}