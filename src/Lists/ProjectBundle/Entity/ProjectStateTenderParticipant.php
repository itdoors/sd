<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectStateTendereTenderParticipant
 */
class ProjectStateTenderParticipant
{
    public function __construct()
    {
        $this->setDatetimeCreate(new \DateTime());
    }
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $summa;

    /**
     * @var boolean
     */
    private $isWinner;

    /**
     * @var string
     */
    private $reason;

    /**
     * @var \DateTime
     */
    private $datetimeCreate;

    /**
     * @var \DateTime
     */
    private $datetimeDeleted;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $participant;

    /**
     * @var \Lists\ProjectBundle\Entity\ProjectStateTender
     */
    private $projectStateTender;


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
     * Set summa
     *
     * @param string $summa
     *
     * @return ProjectStateTenderParticipant
     */
    public function setSumma($summa)
    {
        $this->summa = $summa;
    
        return $this;
    }

    /**
     * Get summa
     *
     * @return string 
     */
    public function getSumma()
    {
        return $this->summa;
    }

    /**
     * Set isWinner
     *
     * @param boolean $isWinner
     * @return ProjectStateTenderParticipant
     */
    public function setIsWinner($isWinner)
    {
        $this->isWinner = $isWinner;
    
        return $this;
    }

    /**
     * Get isWinner
     *
     * @return boolean 
     */
    public function getIsWinner()
    {
        return $this->isWinner;
    }

    /**
     * Set reason
     *
     * @param string $reason
     * @return ProjectStateTenderParticipant
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    
        return $this;
    }

    /**
     * Get reason
     *
     * @return string 
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set datetimeCreate
     *
     * @param \DateTime $datetimeCreate
     * @return ProjectStateTenderParticipant
     */
    public function setDatetimeCreate($datetimeCreate)
    {
        $this->datetimeCreate = $datetimeCreate;
    
        return $this;
    }

    /**
     * Get datetimeCreate
     *
     * @return \DateTime 
     */
    public function getDatetimeCreate()
    {
        return $this->datetimeCreate;
    }

    /**
     * Set datetimeDeleted
     *
     * @param \DateTime $datetimeDeleted
     * @return ProjectStateTenderParticipant
     */
    public function setDatetimeDeleted($datetimeDeleted)
    {
        $this->datetimeDeleted = $datetimeDeleted;
    
        return $this;
    }

    /**
     * Get datetimeDeleted
     *
     * @return \DateTime 
     */
    public function getDatetimeDeleted()
    {
        return $this->datetimeDeleted;
    }

    /**
     * Set participant
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $participant
     * @return ProjectStateTenderParticipant
     */
    public function setParticipant(\Lists\OrganizationBundle\Entity\Organization $participant = null)
    {
        $this->participant = $participant;
    
        return $this;
    }

    /**
     * Get participant
     *
     * @return \Lists\OrganizationBundle\Entity\Organization 
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * Set ProjectStateTender
     *
     * @param \Lists\ProjectBundle\Entity\ProjectStateTender $projectStateTender
     * @return ProjectStateTenderParticipant
     */
    public function setProjectStateTender(\Lists\ProjectBundle\Entity\ProjectStateTender $projectStateTender = null)
    {
        $this->projectStateTender = $projectStateTender;
    
        return $this;
    }

    /**
     * Get projectStateTender
     *
     * @return \Lists\ProjectBundle\Entity\ProjectStateTender 
     */
    public function getProjectStateTender()
    {
        return $this->projectStateTender;
    }
    /**
     * getParticipations
     * 
     * @return mixed[]
     */
    public static function getChoiceList ()
    {
        return array (
            true => 'Yes',
            false => 'No'
        );
    }
}
