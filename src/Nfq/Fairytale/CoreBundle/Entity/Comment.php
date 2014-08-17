<?php

namespace Nfq\Fairytale\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 */
class Comment
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $book;

    /**
     * @var \Nfq\Fairytale\CoreBundle\Entity\User
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->book = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Comment
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
     * Set content
     *
     * @param string $content
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Comment
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Add book
     *
     * @param \Nfq\Fairytale\CoreBundle\Entity\Book $book
     * @return Comment
     */
    public function addBook(\Nfq\Fairytale\CoreBundle\Entity\Book $book)
    {
        $this->book[] = $book;

        return $this;
    }

    /**
     * Remove book
     *
     * @param \Nfq\Fairytale\CoreBundle\Entity\Book $book
     */
    public function removeBook(\Nfq\Fairytale\CoreBundle\Entity\Book $book)
    {
        $this->book->removeElement($book);
    }

    /**
     * Get book
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * Set user
     *
     * @param \Nfq\Fairytale\CoreBundle\Entity\User $user
     * @return Comment
     */
    public function setUser(\Nfq\Fairytale\CoreBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Nfq\Fairytale\CoreBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set book
     *
     * @param \Nfq\Fairytale\CoreBundle\Entity\Book $book
     * @return Comment
     */
    public function setBook(\Nfq\Fairytale\CoreBundle\Entity\Book $book = null)
    {
        $this->book = $book;

        return $this;
    }
}