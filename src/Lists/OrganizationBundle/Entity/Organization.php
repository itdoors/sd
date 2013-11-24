<?php

namespace Lists\OrganizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Organization
 */
class Organization
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $mpk;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $contacts;


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
     * Set mpk
     *
     * @param string $mpk
     * @return Organization
     */
    public function setMpk($mpk)
    {
        $this->mpk = $mpk;
    
        return $this;
    }

    /**
     * Get mpk
     *
     * @return string 
     */
    public function getMpk()
    {
        return $this->mpk;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Organization
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
     * Set address
     *
     * @param string $address
     * @return Organization
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set contacts
     *
     * @param string $contacts
     * @return Organization
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;
    
        return $this;
    }

    /**
     * Get contacts
     *
     * @return string 
     */
    public function getContacts()
    {
        return $this->contacts;
    }
    /**
     * @var \Lists\CityBundle\Entity\City
     */
    private $city;


    /**
     * Set city
     *
     * @param \Lists\CityBundle\Entity\City $city
     * @return Organization
     */
    public function setCity(\Lists\CityBundle\Entity\City $city = null)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return \Lists\CityBundle\Entity\City 
     */
    public function getCity()
    {
        return $this->city;
    }
    /**
     * @var \Lists\LookupBundle\Entity\Lookup
     */
    private $scope;


    /**
     * Set scope
     *
     * @param \Lists\LookupBundle\Entity\Lookup $scope
     * @return Organization
     */
    public function setScope(\Lists\LookupBundle\Entity\Lookup $scope = null)
    {
        $this->scope = $scope;
    
        return $this;
    }

    /**
     * Get scope
     *
     * @return \Lists\LookupBundle\Entity\Lookup 
     */
    public function getScope()
    {
        return $this->scope;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add users
     *
     * @param \SD\UserBundle\User $users
     * @return Organization
     */
    public function addUser(\SD\UserBundle\User $users)
    {
        $this->users[] = $users;
    
        return $this;
    }

    /**
     * Remove users
     *
     * @param \SD\UserBundle\User $users
     */
    public function removeUser(\SD\UserBundle\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}