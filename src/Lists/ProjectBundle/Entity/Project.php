<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 */
class Project
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createDatetime;

    /**
     * @var float
     */
    private $square;

    /**
     * @var string
     */
    private $description;

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
    private $chance;

    /**
     * @var string
     */
    private $worktimeWithclient;

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
     * @var \DateTime
     */
    private $datetimeClosed;

    /**
     * @var \DateTime
     */
    private $closedatetime;

    /**
     * @var string
     */
    private $reasonClosed;

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
    private $nextHandlingDate;

    /**
     * @var \DateTime
     */
    private $createdate;

    /**
     * @var integer
     */
    private $organization_id;

    /**
     * @var integer
     */
    private $result_id;

    /**
     * @var integer
     */
    private $status_id;

    /**
     * @var integer
     */
    private $type_id;

    /**
     * @var boolean
     */
    private $isMarketing;

    /**
     * @var float
     */
    private $pf1;

    /**
     * @var \DateTime
     */
    private $launchDate;

    /**
     * @var integer
     */
    private $employees;

    /**
     * @var \DateTime
     */
    private $deletedDatetime;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $messages;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set createDatetime
     *
     * @param \DateTime $createDatetime
     * @return Project
     */
    public function setCreateDatetime($createDatetime)
    {
        $this->createDatetime = $createDatetime;
    
        return $this;
    }

    /**
     * Get createDatetime
     *
     * @return \DateTime 
     */
    public function getCreateDatetime()
    {
        return $this->createDatetime;
    }

    /**
     * Set square
     *
     * @param float $square
     * @return Project
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
     * Set description
     *
     * @param string $description
     * @return Project
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
     * Set statusChangeDate
     *
     * @param \DateTime $statusChangeDate
     * @return Project
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
     * @return Project
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
     * Set chance
     *
     * @param string $chance
     * @return Project
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
     * @return Project
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
     * Set resultString
     *
     * @param string $resultString
     * @return Project
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
     * @return Project
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
     * @return Project
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
     * Set datetimeClosed
     *
     * @param \DateTime $datetimeClosed
     * @return Project
     */
    public function setDatetimeClosed($datetimeClosed)
    {
        $this->datetimeClosed = $datetimeClosed;
    
        return $this;
    }

    /**
     * Get datetimeClosed
     *
     * @return \DateTime 
     */
    public function getDatetimeClosed()
    {
        return $this->datetimeClosed;
    }

    /**
     * Set closedatetime
     *
     * @param \DateTime $closedatetime
     * @return Project
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
     * Set reasonClosed
     *
     * @param string $reasonClosed
     * @return Project
     */
    public function setReasonClosed($reasonClosed)
    {
        $this->reasonClosed = $reasonClosed;
    
        return $this;
    }

    /**
     * Get reasonClosed
     *
     * @return string 
     */
    public function getReasonClosed()
    {
        return $this->reasonClosed;
    }

    /**
     * Set budgetClient
     *
     * @param string $budgetClient
     * @return Project
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
     * @return Project
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
     * Set nextHandlingDate
     *
     * @param \DateTime $nextHandlingDate
     * @return Project
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
     * Set createdate
     *
     * @param \DateTime $createdate
     * @return Project
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
     * Set organization_id
     *
     * @param integer $organizationId
     * @return Project
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
     * Set result_id
     *
     * @param integer $resultId
     * @return Project
     */
    public function setResultId($resultId)
    {
        $this->result_id = $resultId;
    
        return $this;
    }

    /**
     * Get result_id
     *
     * @return integer 
     */
    public function getResultId()
    {
        return $this->result_id;
    }

    /**
     * Set status_id
     *
     * @param integer $statusId
     * @return Project
     */
    public function setStatusId($statusId)
    {
        $this->status_id = $statusId;
    
        return $this;
    }

    /**
     * Get status_id
     *
     * @return integer 
     */
    public function getStatusId()
    {
        return $this->status_id;
    }

    /**
     * Set type_id
     *
     * @param integer $typeId
     * @return Project
     */
    public function setTypeId($typeId)
    {
        $this->type_id = $typeId;
    
        return $this;
    }

    /**
     * Get type_id
     *
     * @return integer 
     */
    public function getTypeId()
    {
        return $this->type_id;
    }

    /**
     * Set isMarketing
     *
     * @param boolean $isMarketing
     * @return Project
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
     * Set pf1
     *
     * @param float $pf1
     * @return Project
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
     * @return Project
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
     * Set employees
     *
     * @param integer $employees
     * @return Project
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
     * Set deletedDatetime
     *
     * @param \DateTime $deletedDatetime
     * @return Project
     */
    public function setDeletedDatetime($deletedDatetime)
    {
        $this->deletedDatetime = $deletedDatetime;
    
        return $this;
    }

    /**
     * Get deletedDatetime
     *
     * @return \DateTime 
     */
    public function getDeletedDatetime()
    {
        return $this->deletedDatetime;
    }

    /**
     * Add messages
     *
     * @param \Lists\ProjectBundle\Entity\ProjectMessage $messages
     * @return Project
     */
    public function addMessage(\Lists\ProjectBundle\Entity\ProjectMessage $messages)
    {
        $this->messages[] = $messages;
    
        return $this;
    }

    /**
     * Remove messages
     *
     * @param \Lists\ProjectBundle\Entity\ProjectMessage $messages
     */
    public function removeMessage(\Lists\ProjectBundle\Entity\ProjectMessage $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
