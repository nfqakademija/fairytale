<?php

namespace Nfq\Fairytale\CoreBundle\Entity;

/**
 * Reservation
 */
class Reservation
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $takenAt;

    /**
     * @var \DateTime
     */
    private $returnedAt;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Book
     */
    private $book;


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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Reservation
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set takenAt
     *
     * @param \DateTime $takenAt
     * @return Reservation
     */
    public function setTakenAt($takenAt)
    {
        $this->takenAt = $takenAt;

        return $this;
    }

    /**
     * Get takenAt
     *
     * @return \DateTime 
     */
    public function getTakenAt()
    {
        return $this->takenAt;
    }

    /**
     * Set returnedAt
     *
     * @param \DateTime $returnedAt
     * @return Reservation
     */
    public function setReturnedAt($returnedAt)
    {
        $this->returnedAt = $returnedAt;

        return $this;
    }

    /**
     * Get returnedAt
     *
     * @return \DateTime 
     */
    public function getReturnedAt()
    {
        return $this->returnedAt;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Reservation
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set book
     *
     * @param Book $book
     * @return Reservation
     */
    public function setBook(Book $book = null)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     * @return Book
     */
    public function getBook()
    {
        return $this->book;
    }
}
