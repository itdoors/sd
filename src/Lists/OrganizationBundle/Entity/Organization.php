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
        $this->setIsSmeta(false);
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
    /**
     * @var string
     */
    private $shortname;

    /**
     * @var boolean
     */
    private $isSmeta;

    /**
     * @var string
     */
    private $mailingAddress;

    /**
     * @var string
     */
    private $rs;

    /**
     * @var string
     */
    private $edrpou;

    /**
     * @var string
     */
    private $inn;

    /**
     * @var string
     */
    private $certificate;

    /**
     * @var string
     */
    private $shortDescription;

    /**
     * @var string
     */
    private $site;


    /**
     * Set shortname
     *
     * @param string $shortname
     * @return Organization
     */
    public function setShortname($shortname)
    {
        $this->shortname = $shortname;
    
        return $this;
    }

    /**
     * Get shortname
     *
     * @return string 
     */
    public function getShortname()
    {
        return $this->shortname;
    }

    /**
     * Set isSmeta
     *
     * @param boolean $isSmeta
     * @return Organization
     */
    public function setIsSmeta($isSmeta)
    {
        $this->isSmeta = $isSmeta;
    
        return $this;
    }

    /**
     * Get isSmeta
     *
     * @return boolean 
     */
    public function getIsSmeta()
    {
        return $this->isSmeta;
    }

    /**
     * Set mailingAddress
     *
     * @param string $mailingAddress
     * @return Organization
     */
    public function setMailingAddress($mailingAddress)
    {
        $this->mailingAddress = $mailingAddress;
    
        return $this;
    }

    /**
     * Get mailingAddress
     *
     * @return string 
     */
    public function getMailingAddress()
    {
        return $this->mailingAddress;
    }

    /**
     * Set rs
     *
     * @param string $rs
     * @return Organization
     */
    public function setRs($rs)
    {
        $this->rs = $rs;
    
        return $this;
    }

    /**
     * Get rs
     *
     * @return string 
     */
    public function getRs()
    {
        return $this->rs;
    }

    /**
     * Set edrpou
     *
     * @param string $edrpou
     * @return Organization
     */
    public function setEdrpou($edrpou)
    {
        $this->edrpou = $edrpou;
    
        return $this;
    }

    /**
     * Get edrpou
     *
     * @return string 
     */
    public function getEdrpou()
    {
        return $this->edrpou;
    }

    /**
     * Set inn
     *
     * @param string $inn
     * @return Organization
     */
    public function setInn($inn)
    {
        $this->inn = $inn;
    
        return $this;
    }

    /**
     * Get inn
     *
     * @return string 
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Set certificate
     *
     * @param string $certificate
     * @return Organization
     */
    public function setCertificate($certificate)
    {
        $this->certificate = $certificate;
    
        return $this;
    }

    /**
     * Get certificate
     *
     * @return string 
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return Organization
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
    
        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string 
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set site
     *
     * @param string $site
     * @return Organization
     */
    public function setSite($site)
    {
        $this->site = $site;
    
        return $this;
    }

    /**
     * Get site
     *
     * @return string 
     */
    public function getSite()
    {
        return $this->site;
    }
    /**
     * @var \Lists\OrganizationBundle\Entity\OrganizationType
     */
    private $organizationType;

    /**
     * Set organizationType
     *
     * @param \Lists\OrganizationBundle\Entity\OrganizationType $organizationType
     * @return Organization
     */
    public function setOrganizationType(\Lists\OrganizationBundle\Entity\OrganizationType $organizationType = null)
    {
        $this->organizationType = $organizationType;
    
        return $this;
    }

    /**
     * Get organizationType
     *
     * @return \Lists\OrganizationBundle\Entity\OrganizationType 
     */
    public function getOrganizationType()
    {
        return $this->organizationType;
    }

    /**
     * __toString()
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Add users
     *
     * @param \SD\UserBundle\Entity\User $users
     * @return Organization
     */
    public function addUser(\SD\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;
    
        return $this;
    }

    /**
     * Remove users
     *
     * @param \SD\UserBundle\Entity\User $users
     */
    public function removeUser(\SD\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }
}