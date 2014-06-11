<?php

namespace SD\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usercontactinfo
 */
class Usercontactinfo
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $value;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \SD\UserBundle\Entity\Contactinfo
     */
    private $contactinfo;


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
     * @param string $value
     * @return Usercontactinfo
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * @return Usercontactinfo
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

    /**
     * Set contactinfo
     *
     * @param \SD\UserBundle\Entity\Contactinfo $contactinfo
     * @return Usercontactinfo
     */
    public function setContactinfo(\SD\UserBundle\Entity\Contactinfo $contactinfo = null)
    {
        $this->contactinfo = $contactinfo;
    
        return $this;
    }

    /**
     * Get contactinfo
     *
     * @return \SD\UserBundle\Entity\Contactinfo 
     */
    public function getContactinfo()
    {
        return $this->contactinfo;
    } 
}