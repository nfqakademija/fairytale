<?php

namespace Fairytale\CoreBundle\Entity;

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
    private $starCount;

    /**
     * @var \Fairytale\CoreBundle\Entity\User
     */
    private $user;


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
     * Set starCount
     *
     * @param integer $starCount
     * @return Rating
     */
    public function setStarCount($starCount)
    {
        $this->starCount = $starCount;

        return $this;
    }

    /**
     * Get starCount
     *
     * @return integer 
     */
    public function getStarCount()
    {
        return $this->starCount;
    }

    /**
     * Set user
     *
     * @param \Fairytale\CoreBundle\Entity\User $user
     * @return Rating
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
