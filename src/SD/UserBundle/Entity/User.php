<?php
// src/SD/UserBundle/Entity/User.php

namespace SD\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 */
class User extends BaseUser
{
  public function __construct()
  {
    parent::__construct();
  }
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $middleName;

    /**
     * @var string
     */
    private $position;

    /**
     * @var boolean
     */
    private $isBlocked;

    /**
     * @var boolean
     */
    private $isFired;

    /**
     * @var \DateTime
     */
    private $birthday;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $groups;


    /**
     * Set id
     *
     * @param string $id
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
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     * @return User
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
    
        return $this;
    }

    /**
     * Get middleName
     *
     * @return string 
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return User
     */
    public function setPosition($position)
    {
        $this->position = $position;
    
        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set isBlocked
     *
     * @param boolean $isBlocked
     * @return User
     */
    public function setIsBlocked($isBlocked)
    {
        $this->isBlocked = $isBlocked;
    
        return $this;
    }

    /**
     * Get isBlocked
     *
     * @return boolean 
     */
    public function getIsBlocked()
    {
        return $this->isBlocked;
    }

    /**
     * Set isFired
     *
     * @param boolean $isFired
     * @return User
     */
    public function setIsFired($isFired)
    {
        $this->isFired = $isFired;
    
        return $this;
    }

    /**
     * Get isFired
     *
     * @return boolean 
     */
    public function getIsFired()
    {
        return $this->isFired;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    
        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
      $this->salt = $salt;

      return $this;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $organizations;


    /**
     * Add organizations
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organizations
     * @return User
     */
    public function addOrganization(\Lists\OrganizationBundle\Entity\Organization $organizations)
    {
        $this->organizations[] = $organizations;
    
        return $this;
    }

    /**
     * Remove organizations
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organizations
     */
    public function removeOrganization(\Lists\OrganizationBundle\Entity\Organization $organizations)
    {
        $this->organizations->removeElement($organizations);
    }

    /**
     * Get organizations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrganizations()
    {
        return $this->organizations;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $handlings;


    /**
     * Add handlings
     *
     * @param \Lists\HandlingBundle\Entity\Handling $handlings
     * @return User
     */
    public function addHandling(\Lists\HandlingBundle\Entity\Handling $handlings)
    {
        $this->handlings[] = $handlings;
    
        return $this;
    }

    /**
     * Remove handlings
     *
     * @param \Lists\HandlingBundle\Entity\Handling $handlings
     */
    public function removeHandling(\Lists\HandlingBundle\Entity\Handling $handlings)
    {
        $this->handlings->removeElement($handlings);
    }

    /**
     * Get handlings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHandlings()
    {
        return $this->handlings;
    }
}