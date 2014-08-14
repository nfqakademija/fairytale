<?php

namespace Fairytale\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Book
 */
class Book
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var integer
     */
    private $year;

    /**
     * @var \Fairytale\CoreBundle\Entity\Book
     */
    private $proposal;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $categories;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $authors;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->authors = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Book
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Book
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set year
     *
     * @param integer $year
     * @return Book
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set proposal
     *
     * @param \Fairytale\CoreBundle\Entity\Book $proposal
     * @return Book
     */
    public function setProposal(\Fairytale\CoreBundle\Entity\Book $proposal = null)
    {
        $this->proposal = $proposal;

        return $this;
    }

    /**
     * Get proposal
     *
     * @return \Fairytale\CoreBundle\Entity\Book 
     */
    public function getProposal()
    {
        return $this->proposal;
    }

    /**
     * Add categories
     *
     * @param \Fairytale\CoreBundle\Entity\Category $categories
     * @return Book
     */
    public function addCategory(\Fairytale\CoreBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Fairytale\CoreBundle\Entity\Category $categories
     */
    public function removeCategory(\Fairytale\CoreBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add authors
     *
     * @param \Fairytale\CoreBundle\Entity\Author $authors
     * @return Book
     */
    public function addAuthor(\Fairytale\CoreBundle\Entity\Author $authors)
    {
        $this->authors[] = $authors;

        return $this;
    }

    /**
     * Remove authors
     *
     * @param \Fairytale\CoreBundle\Entity\Author $authors
     */
    public function removeAuthor(\Fairytale\CoreBundle\Entity\Author $authors)
    {
        $this->authors->removeElement($authors);
    }

    /**
     * Get authors
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAuthors()
    {
        return $this->authors;
    }
}
