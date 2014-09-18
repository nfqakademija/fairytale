<?php

namespace Nfq\Fairytale\CoreBundle\Entity;

use Nfq\Fairytale\CoreBundle\Upload\UploadInterface;

/**
 * Image
 */
class Image implements UploadInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $originalName;

    /**
     * @var string
     */
    private $storedName;

    /**
     * @var \DateTime
     */
    private $createdAt;


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
     * Set originalName
     *
     * @param string $originalName
     * @return Image
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * Get originalName
     *
     * @return string 
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * Set storedName
     *
     * @param string $storedName
     * @return Image
     */
    public function setStoredName($storedName)
    {
        $this->storedName = $storedName;

        return $this;
    }

    /**
     * Get storedName
     *
     * @return string 
     */
    public function getStoredName()
    {
        return $this->storedName;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Image
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
}
