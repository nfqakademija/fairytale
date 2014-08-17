<?php

namespace Nfq\Fairytale\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rating
 */
class Rating
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $value;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \Nfq\Fairytale\CoreBundle\Entity\User
     */
    private $user;

    /**
     * @var \Nfq\Fairytale\CoreBundle\Entity\Book
     */
    private $book;


    /**
     * Set id
     *
     * @param integer $id
     * @return Rating
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
     * Set value
     *
     * @param integer $value
     * @return Rating
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Rating
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
     * Set user
     *
     * @param \Nfq\Fairytale\CoreBundle\Entity\User $user
     * @return Rating
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
     * @return Rating
     */
    public function setBook(\Nfq\Fairytale\CoreBundle\Entity\Book $book = null)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     * @return \Nfq\Fairytale\CoreBundle\Entity\Book 
     */
    public function getBook()
    {
        return $this->book;
    }
}
