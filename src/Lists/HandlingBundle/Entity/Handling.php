<?php

namespace Lists\HandlingBundle\Entity;

/**
 * Handling
 */
class Handling
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $number;

    /**
     * @var \DateTime
     */
    protected $createdatetime;

    /**
     * @var string
     */
    protected $statusDescription;

    /**
     * @var \DateTime
     */
    protected $statusChangeDate;

    /**
     * @var string
     */
    protected $serviceOffered;

    /**
     * @var string
     */
    protected $budget;

    /**
     * @var float
     */
    protected $square;

    /**
     * @var string
     */
    protected $chance;

    /**
     * @var string
     */
    protected $worktimeWithclient;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $resultString;

    /**
     * @var boolean
     */
    protected $statusAdmin;

    /**
     * @var boolean
     */
    protected $isClosed;

    /**
     * @var string
     */
    protected $budgetClient;

    /**
     * @var \DateTime
     */
    protected $lastHandlingDate;

    /**
     * @var \DateTime
     */
    protected $createdate;

    /**
     * @var \Lists\HandlingBundle\Entity\HandlingResult
     */
    protected $result;

    /**
     * @var \Lists\HandlingBundle\Entity\HandlingStatus
     */
    protected $status;

    /**
     * @var \Lists\HandlingBundle\Entity\HandlingType
     */
    protected $type;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    protected $user;

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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
     * @return Handling
     */
    public function setResult(\Lists\HandlingBundle\Entity\HandlingResult $result = null)
    {
        $this->result = $result;

        if ($result) {
            $this->setResultId($result->getId());
        }

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
     *
     * @return Handling
     */
    public function setStatus(\Lists\HandlingBundle\Entity\HandlingStatus $status = null)
    {
        $this->status = $status;

        if ($status) {
            $this->setStatusId($status->getId());
        }

        $this->setStatusChangeDate(new \DateTime());

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
     *
     * @return Handling
     */
    public function setType(\Lists\HandlingBundle\Entity\HandlingType $type = null)
    {
        $this->type = $type;

        if ($type) {
            $this->setTypeId($type->getId());
        }

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
     *
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
    protected $organization_id;

    /**
     * Set organization_id
     *
     * @param integer $organizationId
     *
     * @return Handling
     */
    public function setOrganizationId($organizationId)
    {
        // @codingStandardsIgnoreStart
        $this->organization_id = $organizationId;
        // @codingStandardsIgnoreEnd
        return $this;
    }

    /**
     * Get organization_id
     *
     * @return integer
     */
    public function getOrganizationId()
    {
        // @codingStandardsIgnoreStart
        return $this->organization_id;
        // @codingStandardsIgnoreEnd
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $users;

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
     *
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
    protected $organization;

    /**
     * Set organization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organization
     *
     * @return Handling
     */
    public function setOrganization(\Lists\OrganizationBundle\Entity\Organization $organization = null)
    {
        $this->organization = $organization;

        if ($organization) {
            $this->setOrganizationId($organization->getId());
        }

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

    /**
     * @var integer
     */
    protected $result_id;

    /**
     * @var integer
     */
    protected $status_id;

    /**
     * @var integer
     */
    protected $type_id;

    /**
     * Set result_id
     *
     * @param integer $resultId
     *
     * @return Handling
     */
    public function setResultId($resultId)
    {
        // @codingStandardsIgnoreStart
        $this->result_id = $resultId;
        // @codingStandardsIgnoreEnd
        return $this;
    }

    /**
     * Get result_id
     *
     * @return integer
     */
    public function getResultId()
    {
        // @codingStandardsIgnoreStart
        return $this->result_id;
        // @codingStandardsIgnoreEnd
    }

    /**
     * Set status_id
     *
     * @param integer $statusId
     *
     * @return Handling
     */
    public function setStatusId($statusId)
    {
        // @codingStandardsIgnoreStart
        $this->status_id = $statusId;
        // @codingStandardsIgnoreEnd

        $this->setStatusChangeDate(new \DateTime());

        return $this;
    }

    /**
     * Get status_id
     *
     * @return integer
     */
    public function getStatusId()
    {
        // @codingStandardsIgnoreStart
        return $this->status_id;
        // @codingStandardsIgnoreEnd
    }

    /**
     * Set type_id
     *
     * @param integer $typeId
     *
     * @return Handling
     */
    public function setTypeId($typeId)
    {
        // @codingStandardsIgnoreStart
        $this->type_id = $typeId;
        // @codingStandardsIgnoreEnd
        return $this;
    }

    /**
     * Get type_id
     *
     * @return integer
     */
    public function getTypeId()
    {
        // @codingStandardsIgnoreStart
        return $this->type_id;
        // @codingStandardsIgnoreEnd
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $handlingServices;

    /**
     * Add handlingServices
     *
     * @param \Lists\HandlingBundle\Entity\HandlingService $handlingServices
     *
     * @return Handling
     */
    public function addHandlingService(\Lists\HandlingBundle\Entity\HandlingService $handlingServices)
    {
        $this->handlingServices[] = $handlingServices;

        return $this;
    }

    /**
     * Remove handlingServices
     *
     * @param \Lists\HandlingBundle\Entity\HandlingService $handlingServices
     */
    public function removeHandlingService(\Lists\HandlingBundle\Entity\HandlingService $handlingServices)
    {
        $this->handlingServices->removeElement($handlingServices);
    }

    /**
     * Get handlingServices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHandlingServices()
    {
        return $this->handlingServices;
    }

    /**
     * __toStrong
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getId() . ' - ' . $this->getOrganization();
    }

    /**
     * @var \DateTime
     */
    protected $nextHandlingDate;

    /**
     * Set nextHandlingDate
     *
     * @param \DateTime $nextHandlingDate
     *
     * @return Handling
     */
    public function setNextHandlingDate($nextHandlingDate)
    {
        $this->nextHandlingDate = $nextHandlingDate;

        return $this;
    }

    /**
     * Get nextHandlingDate
     *
     * @return \DateTime
     */
    public function getNextHandlingDate()
    {
        return $this->nextHandlingDate;
    }

    /**
     * @var \DateTime
     */
    protected $closedatetime;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    protected $closer;

    /**
     * Set closedatetime
     *
     * @param \DateTime $closedatetime
     *
     * @return Handling
     */
    public function setClosedatetime($closedatetime)
    {
        $this->closedatetime = $closedatetime;

        return $this;
    }

    /**
     * Get closedatetime
     *
     * @return \DateTime
     */
    public function getClosedatetime()
    {
        return $this->closedatetime;
    }

    /**
     * Set closer
     *
     * @param \SD\UserBundle\Entity\User $closer
     *
     * @return Handling
     */
    public function setCloser(\SD\UserBundle\Entity\User $closer = null)
    {
        $this->closer = $closer;

        return $this;
    }

    /**
     * Get closer
     *
     * @return \SD\UserBundle\Entity\User
     */
    public function getCloser()
    {
        return $this->closer;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $HandlingMessages;

    /**
     * Add HandlingMessages
     *
     * @param \Lists\HandlingBundle\Entity\HandlingMessage $handlingMessages
     *
     * @return Handling
     */
    public function addHandlingMessage(\Lists\HandlingBundle\Entity\HandlingMessage $handlingMessages)
    {
        // @codingStandardsIgnoreStart
        $this->HandlingMessages[] = $handlingMessages;
        // @codingStandardsIgnoreEnd
        return $this;
    }

    /**
     * Remove HandlingMessages
     *
     * @param \Lists\HandlingBundle\Entity\HandlingMessage $handlingMessages
     */
    public function removeHandlingMessage(\Lists\HandlingBundle\Entity\HandlingMessage $handlingMessages)
    {
        // @codingStandardsIgnoreStart
        $this->HandlingMessages->removeElement($handlingMessages);
        // @codingStandardsIgnoreEnd
    }

    /**
     * Get HandlingMessages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHandlingMessages()
    {
        // @codingStandardsIgnoreStart
        return $this->HandlingMessages;
        // @codingStandardsIgnoreEnd
    }
    /**
     * @var boolean
     */
    private $isMarketing;

    /**
     * Set isMarketing
     *
     * @param boolean $isMarketing
     *
     * @return Handling
     */
    public function setIsMarketing($isMarketing)
    {
        $this->isMarketing = $isMarketing;

        return $this;
    }

    /**
     * Get isMarketing
     *
     * @return boolean
     */
    public function getIsMarketing()
    {
        return $this->isMarketing;
    }
    /**
     * @var float
     */
    private $pf1;

    /**
     * @var \DateTime
     */
    private $launchDate;

    /**
     * Set pf1
     *
     * @param float $pf1
     *
     * @return Handling
     */
    public function setPf1($pf1)
    {
        $this->pf1 = $pf1;

        return $this;
    }

    /**
     * Get pf1
     *
     * @return float
     */
    public function getPf1()
    {
        return $this->pf1;
    }

    /**
     * Set launchDate
     *
     * @param \DateTime $launchDate
     *
     * @return Handling
     */
    public function setLaunchDate($launchDate)
    {
        $this->launchDate = $launchDate;

        return $this;
    }

    /**
     * Get launchDate
     *
     * @return \DateTime
     */
    public function getLaunchDate()
    {
        return $this->launchDate;
    }
    /**
     * @var integer
     */
    private $employees;

    /**
     * Set employees
     *
     * @param integer $employees
     *
     * @return Handling
     */
    public function setEmployees($employees)
    {
        $this->employees = $employees;

        return $this;
    }

    /**
     * Get employees
     *
     * @return integer
     */
    public function getEmployees()
    {
        return $this->employees;
    }

    /**
     * Sets launchDate from launchDateString
     *
     * @param string $launchDateString
     */
    public function setLaunchDateString($launchDateString)
    {
        if ($launchDateString) {
            $this->setLaunchDate(new \DateTime($launchDateString));
        }
    }
}
