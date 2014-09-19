<?php

namespace Nfq\Fairytale\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Category
 */
class Category
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
     * @var Collection
     */
    private $books;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->books = new ArrayCollection();
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
     * @return Category
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
     * Add books
     *
     * @param Book $books
     * @return Category
     */
    public function addBook(Book $books)
    {
        $this->books[] = $books;

        return $this;
    }

    /**
     * Remove books
     *
     * @param Book $books
     */
    public function removeBook(Book $books)
    {
        $this->books->removeElement($books);
    }

    /**
     * Get books
     *
     * @return Collection
     */
    public function getBooks()
    {
        return $this->books;
    }
}
