<?php

namespace Nfq\Fairytale\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nfq\Fairytale\CoreBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $categories = ['Technical', 'Design', 'Business', 'Stocks and Trends', 'Project Management', 'Leisure'];
        foreach ($categories as $title) {
            $category = new Category();
            $category->setTitle($title);
            $manager->persist($category);

            $this->addReference('category-' . $title, $category);
        }

        $manager->flush();
    }
}