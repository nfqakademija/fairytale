<?php

namespace Nfq\Fairytale\CoreBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nfq\Fairytale\CoreBundle\Entity\Category;

class LoadCategoryData implements FixtureInterface
{

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
        }

        $manager->flush();
    }
}