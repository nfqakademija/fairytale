<?php

namespace Nfq\Fairytale\CoreBundle\Entity;

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
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $genre;

    /**
     * @var \Nfq\Fairytale\CoreBundle\Entity\Rating
     */
    private $ratings;

    /**
     * @var \Nfq\Fairytale\CoreBundle\Entity\Comment
     */
    private $comments;

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
     * Set description
     *
     * @param string $description
     * @return Book
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set genre
     *
     * @param string $genre
     * @return Book
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string 
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set ratings
     *
     * @param \Nfq\Fairytale\CoreBundle\Entity\Rating $ratings
     * @return Book
     */
    public function setRatings(\Nfq\Fairytale\CoreBundle\Entity\Rating $ratings = null)
    {
        $this->ratings = $ratings;

        return $this;
    }

    /**
     * Get ratings
     *
     * @return \Nfq\Fairytale\CoreBundle\Entity\Rating 
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * Set comments
     *
     * @param \Nfq\Fairytale\CoreBundle\Entity\Comment $comments
     * @return Book
     */
    public function setComments(\Nfq\Fairytale\CoreBundle\Entity\Comment $comments = null)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return \Nfq\Fairytale\CoreBundle\Entity\Comment 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add categories
     *
     * @param \Nfq\Fairytale\CoreBundle\Entity\Category $categories
     * @return Book
     */
    public function addCategory(\Nfq\Fairytale\CoreBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Nfq\Fairytale\CoreBundle\Entity\Category $categories
     */
    public function removeCategory(\Nfq\Fairytale\CoreBundle\Entity\Category $categories)
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
     * @param \Nfq\Fairytale\CoreBundle\Entity\Author $authors
     * @return Book
     */
    public function addAuthor(\Nfq\Fairytale\CoreBundle\Entity\Author $authors)
    {
        $this->authors[] = $authors;

        return $this;
    }

    /**
     * Remove authors
     *
     * @param \Nfq\Fairytale\CoreBundle\Entity\Author $authors
     */
    public function removeAuthor(\Nfq\Fairytale\CoreBundle\Entity\Author $authors)
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

    /**
     * Add ratings
     *
     * @param \Nfq\Fairytale\CoreBundle\Entity\Rating $ratings
     * @return Book
     */
    public function addRating(\Nfq\Fairytale\CoreBundle\Entity\Rating $ratings)
    {
        $this->ratings[] = $ratings;

        return $this;
    }

    /**
     * Remove ratings
     *
     * @param \Nfq\Fairytale\CoreBundle\Entity\Rating $ratings
     */
    public function removeRating(\Nfq\Fairytale\CoreBundle\Entity\Rating $ratings)
    {
        $this->ratings->removeElement($ratings);
    }

    /**
     * Add comments
     *
     * @param \Nfq\Fairytale\CoreBundle\Entity\Comment $comments
     * @return Book
     */
    public function addComment(\Nfq\Fairytale\CoreBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Nfq\Fairytale\CoreBundle\Entity\Comment $comments
     */
    public function removeComment(\Nfq\Fairytale\CoreBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }
}
