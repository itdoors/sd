<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Handling
 */
class Handling
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $number;

    /**
     * @var \DateTime
     */
    private $createdatetime;

    /**
     * @var string
     */
    private $statusDescription;

    /**
     * @var \DateTime
     */
    private $statusChangeDate;

    /**
     * @var string
     */
    private $serviceOffered;

    /**
     * @var string
     */
    private $budget;

    /**
     * @var float
     */
    private $square;

    /**
     * @var string
     */
    private $chance;

    /**
     * @var string
     */
    private $worktimeWithclient;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $resultString;

    /**
     * @var boolean
     */
    private $statusAdmin;

    /**
     * @var boolean
     */
    private $isClosed;

    /**
     * @var string
     */
    private $budgetClient;

    /**
     * @var \DateTime
     */
    private $lastHandlingDate;

    /**
     * @var \DateTime
     */
    private $createdate;

    /**
     * @var \Lists\HandlingBundle\Entity\HandlingResult
     */
    private $result;

    /**
     * @var \Lists\HandlingBundle\Entity\HandlingStatus
     */
    private $status;

    /**
     * @var \Lists\HandlingBundle\Entity\HandlingType
     */
    private $type;

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
     * Set number
     *
     * @param string $number
     * @return Handling
     */
    public function setNumber($number)
    {
        $this->number = $number;
    
        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set createdatetime
     *
     * @param \DateTime $createdatetime
     * @return Handling
     */
    public function setCreatedatetime($createdatetime)
    {
        $this->createdatetime = $createdatetime;
    
        return $this;
    }

    /**
     * Get createdatetime
     *
     * @return \DateTime 
     */
    public function getCreatedatetime()
    {
        return $this->createdatetime;
    }

    /**
     * Set statusDescription
     *
     * @param string $statusDescription
     * @return Handling
     */
    public function setStatusDescription($statusDescription)
    {
        $this->statusDescription = $statusDescription;
    
        return $this;
    }

    /**
     * Get statusDescription
     *
     * @return string 
     */
    public function getStatusDescription()
    {
        return $this->statusDescription;
    }

    /**
     * Set statusChangeDate
     *
     * @param \DateTime $statusChangeDate
     * @return Handling
     */
    public function setStatusChangeDate($statusChangeDate)
    {
        $this->statusChangeDate = $statusChangeDate;
    
        return $this;
    }

    /**
     * Get statusChangeDate
     *
     * @return \DateTime 
     */
    public function getStatusChangeDate()
    {
        return $this->statusChangeDate;
    }

    /**
     * Set serviceOffered
     *
     * @param string $serviceOffered
     * @return Handling
     */
    public function setServiceOffered($serviceOffered)
    {
        $this->serviceOffered = $serviceOffered;
    
        return $this;
    }

    /**
     * Get serviceOffered
     *
     * @return string 
     */
    public function getServiceOffered()
    {
        return $this->serviceOffered;
    }

    /**
     * Set budget
     *
     * @param string $budget
     * @return Handling
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;
    
        return $this;
    }

    /**
     * Get budget
     *
     * @return string 
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Set square
     *
     * @param float $square
     * @return Handling
     */
    public function setSquare($square)
    {
        $this->square = $square;
    
        return $this;
    }

    /**
     * Get square
     *
     * @return float 
     */
    public function getSquare()
    {
        return $this->square;
    }

    /**
     * Set chance
     *
     * @param string $chance
     * @return Handling
     */
    public function setChance($chance)
    {
        $this->chance = $chance;
    
        return $this;
    }

    /**
     * Get chance
     *
     * @return string 
     */
    public function getChance()
    {
        return $this->chance;
    }

    /**
     * Set worktimeWithclient
     *
     * @param string $worktimeWithclient
     * @return Handling
     */
    public function setWorktimeWithclient($worktimeWithclient)
    {
        $this->worktimeWithclient = $worktimeWithclient;
    
        return $this;
    }

    /**
     * Get worktimeWithclient
     *
     * @return string 
     */
    public function getWorktimeWithclient()
    {
        return $this->worktimeWithclient;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Handling
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
     * Set resultString
     *
     * @param string $resultString
     * @return Handling
     */
    public function setResultString($resultString)
    {
        $this->resultString = $resultString;
    
        return $this;
    }

    /**
     * Get resultString
     *
     * @return string 
     */
    public function getResultString()
    {
        return $this->resultString;
    }

    /**
     * Set statusAdmin
     *
     * @param boolean $statusAdmin
     * @return Handling
     */
    public function setStatusAdmin($statusAdmin)
    {
        $this->statusAdmin = $statusAdmin;
    
        return $this;
    }

    /**
     * Get statusAdmin
     *
     * @return boolean 
     */
    public function getStatusAdmin()
    {
        return $this->statusAdmin;
    }

    /**
     * Set isClosed
     *
     * @param boolean $isClosed
     * @return Handling
     */
    public function setIsClosed($isClosed)
    {
        $this->isClosed = $isClosed;
    
        return $this;
    }

    /**
     * Get isClosed
     *
     * @return boolean 
     */
    public function getIsClosed()
    {
        return $this->isClosed;
    }

    /**
     * Set budgetClient
     *
     * @param string $budgetClient
     * @return Handling
     */
    public function setBudgetClient($budgetClient)
    {
        $this->budgetClient = $budgetClient;
    
        return $this;
    }

    /**
     * Get budgetClient
     *
     * @return string 
     */
    public function getBudgetClient()
    {
        return $this->budgetClient;
    }

    /**
     * Set lastHandlingDate
     *
     * @param \DateTime $lastHandlingDate
     * @return Handling
     */
    public function setLastHandlingDate($lastHandlingDate)
    {
        $this->lastHandlingDate = $lastHandlingDate;
    
        return $this;
    }

    /**
     * Get lastHandlingDate
     *
     * @return \DateTime 
     */
    public function getLastHandlingDate()
    {
        return $this->lastHandlingDate;
    }

    /**
     * Set createdate
     *
     * @param \DateTime $createdate
     * @return Handling
     */
    public function setCreatedate($createdate)
    {
        $this->createdate = $createdate;
    
        return $this;
    }

    /**
     * Get createdate
     *
     * @return \DateTime 
     */
    public function getCreatedate()
    {
        return $this->createdate;
    }

    /**
     * Set result
     *
     * @param \Lists\HandlingBundle\Entity\HandlingResult $result
     * @return Handling
     */
    public function setResult(\Lists\HandlingBundle\Entity\HandlingResult $result = null)
    {
        $this->result = $result;
    
        return $this;
    }

    /**
     * Get result
     *
     * @return \Lists\HandlingBundle\Entity\HandlingResult 
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set status
     *
     * @param \Lists\HandlingBundle\Entity\HandlingStatus $status
     * @return Handling
     */
    public function setStatus(\Lists\HandlingBundle\Entity\HandlingStatus $status = null)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return \Lists\HandlingBundle\Entity\HandlingStatus 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set type
     *
     * @param \Lists\HandlingBundle\Entity\HandlingType $type
     * @return Handling
     */
    public function setType(\Lists\HandlingBundle\Entity\HandlingType $type = null)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return \Lists\HandlingBundle\Entity\HandlingType 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * @return Handling
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
     * @var integer
     */
    private $organization_id;


    /**
     * Set organization_id
     *
     * @param integer $organizationId
     * @return Handling
     */
    public function setOrganizationId($organizationId)
    {
        $this->organization_id = $organizationId;
    
        return $this;
    }

    /**
     * Get organization_id
     *
     * @return integer 
     */
    public function getOrganizationId()
    {
        return $this->organization_id;
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
     * @param \SD\UserBundle\Entity\User $users
     * @return Handling
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
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $organization;


    /**
     * Set organization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organization
     * @return Handling
     */
    public function setOrganization(\Lists\OrganizationBundle\Entity\Organization $organization = null)
    {
        $this->organization = $organization;
    
        return $this;
    }

    /**
     * Get organization
     *
     * @return \Lists\OrganizationBundle\Entity\Organization 
     */
    public function getOrganization()
    {
        return $this->organization;
    }
}