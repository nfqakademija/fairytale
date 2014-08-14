<?php

namespace Fairytale\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 */
class User
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $surname;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $comments;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $proposals;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $ratings;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $votes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->proposals = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ratings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return User
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
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Add comments
     *
     * @param \Fairytale\CoreBundle\Entity\Comment $comments
     * @return User
     */
    public function addComment(\Fairytale\CoreBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Fairytale\CoreBundle\Entity\Comment $comments
     */
    public function removeComment(\Fairytale\CoreBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add proposals
     *
     * @param \Fairytale\CoreBundle\Entity\Proposal $proposals
     * @return User
     */
    public function addProposal(\Fairytale\CoreBundle\Entity\Proposal $proposals)
    {
        $this->proposals[] = $proposals;

        return $this;
    }

    /**
     * Remove proposals
     *
     * @param \Fairytale\CoreBundle\Entity\Proposal $proposals
     */
    public function removeProposal(\Fairytale\CoreBundle\Entity\Proposal $proposals)
    {
        $this->proposals->removeElement($proposals);
    }

    /**
     * Get proposals
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProposals()
    {
        return $this->proposals;
    }

    /**
     * Add ratings
     *
     * @param \Fairytale\CoreBundle\Entity\Rating $ratings
     * @return User
     */
    public function addRating(\Fairytale\CoreBundle\Entity\Rating $ratings)
    {
        $this->ratings[] = $ratings;

        return $this;
    }

    /**
     * Remove ratings
     *
     * @param \Fairytale\CoreBundle\Entity\Rating $ratings
     */
    public function removeRating(\Fairytale\CoreBundle\Entity\Rating $ratings)
    {
        $this->ratings->removeElement($ratings);
    }

    /**
     * Get ratings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * Add votes
     *
     * @param \Fairytale\CoreBundle\Entity\Vote $votes
     * @return User
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
}
