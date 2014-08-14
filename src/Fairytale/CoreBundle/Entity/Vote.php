<?php

namespace Fairytale\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vote
 */
class Vote
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $vote;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var \Fairytale\CoreBundle\Entity\Proposal
     */
    private $proposal;

    /**
     * @var \Fairytale\CoreBundle\Entity\User
     */
    private $user;


    /**
     * Set id
     *
     * @param integer $id
     * @return Vote
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
     * Set vote
     *
     * @param boolean $vote
     * @return Vote
     */
    public function setVote($vote)
    {
        $this->vote = $vote;

        return $this;
    }

    /**
     * Get vote
     *
     * @return boolean 
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Vote
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
     * Set proposal
     *
     * @param \Fairytale\CoreBundle\Entity\Proposal $proposal
     * @return Vote
     */
    public function setProposal(\Fairytale\CoreBundle\Entity\Proposal $proposal = null)
    {
        $this->proposal = $proposal;

        return $this;
    }

    /**
     * Get proposal
     *
     * @return \Fairytale\CoreBundle\Entity\Proposal 
     */
    public function getProposal()
    {
        return $this->proposal;
    }

    /**
     * Set user
     *
     * @param \Fairytale\CoreBundle\Entity\User $user
     * @return Vote
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
