<?php

namespace Fairytale\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proposal
 */
class Proposal
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var \Fairytale\CoreBundle\Entity\Book
     */
    private $book;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $votes;

    /**
     * @var \Fairytale\CoreBundle\Entity\User
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Proposal
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
     * Set date
     *
     * @param \DateTime $date
     * @return Proposal
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set book
     *
     * @param \Fairytale\CoreBundle\Entity\Book $book
     * @return Proposal
     */
    public function setBook(\Fairytale\CoreBundle\Entity\Book $book = null)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     * @return \Fairytale\CoreBundle\Entity\Book 
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * Add votes
     *
     * @param \Fairytale\CoreBundle\Entity\Vote $votes
     * @return Proposal
     */
    public function addVote(\Fairytale\CoreBundle\Entity\Vote $votes)
    {
        $this->votes[] = $votes;

        return $this;
    }

    /**
     * Remove votes
     *
     * @param \Fairytale\CoreBundle\Entity\Vote $votes
     */
    public function removeVote(\Fairytale\CoreBundle\Entity\Vote $votes)
    {
        $this->votes->removeElement($votes);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set user
     *
     * @param \Fairytale\CoreBundle\Entity\User $user
     * @return Proposal
     */
    public function setUser(\Fairytale\CoreBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Fairytale\CoreBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
