<?php

namespace SD\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Staff
 */
class Staff
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $mobilephone;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $stuffclass;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $user;


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
     * Set mobilephone
     *
     * @param string $mobilephone
     * @return Staff
     */
    public function setMobilephone($mobilephone)
    {
        $this->mobilephone = $mobilephone;
    
        return $this;
    }

    /**
     * Get mobilephone
     *
     * @return string 
     */
    public function getMobilephone()
    {
        return $this->mobilephone;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Staff
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
     * Set stuffclass
     *
     * @param string $stuffclass
     * @return Staff
     */
    public function setStuffclass($stuffclass)
    {
        $this->stuffclass = $stuffclass;
    
        return $this;
    }

    /**
     * Get stuffclass
     *
     * @return string 
     */
    public function getStuffclass()
    {
        return $this->stuffclass;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * @return Staff
     */
    public function setUser(\SD\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}