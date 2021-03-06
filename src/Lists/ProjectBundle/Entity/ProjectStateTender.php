<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectStateTender
 */
class ProjectStateTender extends Project
{

    /**
     * @var string
     */
    private $vdz;

    /**
     * @var integer
     */
    private $advert;

    /**
     * @var string
     */
    private $typeOfProcedure;

    /**
     * @var string
     */
    private $place;

    /**
     * @var string
     */
    private $delivery;

    /**
     * @var \DateTime
     */
    private $datetimeDeadline;

    /**
     * @var \DateTime
     */
    private $datetimeOpening;

    /**
     * @var string
     */
    private $software;

    /**
     * @var boolean
     */
    private $isParticipation;

    /**
     * @var string
     */
    private $reason;

    /**
     * @var string
     */
    private $budget;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $kveds;

    /**
     * Constructor
     */
    public function __construct ()
    {
        parent::__construct();
        $this->kveds = new \Doctrine\Common\Collections\ArrayCollection();
        $this->participants = new \Doctrine\Common\Collections\ArrayCollection();
        $this->services = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setStatusAccess(true);
    }
    /**
     * Set vdz
     *
     * @param string $vdz
     * @return ProjectStateTender
     */
    public function setVdz ($vdz)
    {
        $this->vdz = $vdz;

        return $this;
    }
    /**
     * Get vdz
     *
     * @return string 
     */
    public function getVdz ()
    {
        return $this->vdz;
    }
    /**
     * Set advert
     *
     * @param integer $advert
     *
     * @return ProjectStateTender
     */
    public function setAdvert ($advert)
    {
        $this->advert = $advert;

        return $this;
    }
    /**
     * Get advert
     *
     * @return integer 
     */
    public function getAdvert ()
    {
        return $this->advert;
    }
    /**
     * Set typeOfProcedure
     *
     * @param string $typeOfProcedure
     *
     * @return ProjectStateTender
     */
    public function setTypeOfProcedure ($typeOfProcedure)
    {
        $this->typeOfProcedure = $typeOfProcedure;

        return $this;
    }
    /**
     * Get typeOfProcedure
     *
     * @return string 
     */
    public function getTypeOfProcedure ()
    {
        return $this->typeOfProcedure;
    }
    /**
     * Set place
     *
     * @param string $place
     *
     * @return ProjectStateTender
     */
    public function setPlace ($place)
    {
        $this->place = $place;

        return $this;
    }
    /**
     * Get place
     *
     * @return string 
     */
    public function getPlace ()
    {
        return $this->place;
    }
    /**
     * Set delivery
     *
     * @param string $delivery
     *
     * @return ProjectStateTender
     */
    public function setDelivery ($delivery)
    {
        $this->delivery = $delivery;

        return $this;
    }
    /**
     * Get delivery
     *
     * @return string 
     */
    public function getDelivery ()
    {
        return $this->delivery;
    }
    /**
     * Set datetimeDeadline
     *
     * @param \DateTime $datetimeDeadline
     *
     * @return ProjectStateTender
     */
    public function setDatetimeDeadline ($datetimeDeadline)
    {
        $this->datetimeDeadline = $datetimeDeadline;

        return $this;
    }
    /**
     * Get datetimeDeadline
     *
     * @return \DateTime 
     */
    public function getDatetimeDeadline ()
    {
        return $this->datetimeDeadline;
    }
    /**
     * Set datetimeOpening
     *
     * @param \DateTime $datetimeOpening
     *
     * @return ProjectStateTender
     */
    public function setDatetimeOpening ($datetimeOpening)
    {
        $this->datetimeOpening = $datetimeOpening;

        return $this;
    }
    /**
     * Get datetimeOpening
     *
     * @return \DateTime 
     */
    public function getDatetimeOpening ()
    {
        return $this->datetimeOpening;
    }
    /**
     * Set software
     *
     * @param string $software
     *
     * @return ProjectStateTender
     */
    public function setSoftware ($software)
    {
        $this->software = $software;

        return $this;
    }
    /**
     * Get software
     *
     * @return string 
     */
    public function getSoftware ()
    {
        return $this->software;
    }
    /**
     * Set isParticipation
     *
     * @param boolean $isParticipation
     *
     * @return ProjectStateTender
     */
    public function setIsParticipation ($isParticipation)
    {
        $this->isParticipation = $isParticipation;

        return $this;
    }
    /**
     * Get isParticipation
     *
     * @return boolean 
     */
    public function getIsParticipation ()
    {
        return $this->isParticipation;
    }
    /**
     * Set reason
     *
     * @param string $reason
     *
     * @return ProjectStateTender
     */
    public function setReason ($reason)
    {
        $this->reason = $reason;

        return $this;
    }
    /**
     * Get reason
     *
     * @return string 
     */
    public function getReason ()
    {
        return $this->reason;
    }
    /**
     * Set budget
     *
     * @param string $budget
     *
     * @return ProjectStateTender
     */
    public function setBudget ($budget)
    {
        $this->budget = $budget;

        return $this;
    }
    /**
     * Get budget
     *
     * @return string 
     */
    public function getBudget ()
    {
        return $this->budget;
    }
    /**
     * Add kveds
     *
     * @param \Lists\OrganizationBundle\Entity\Kved $kveds
     *
     * @return ProjectStateTender
     */
    public function addKved (\Lists\OrganizationBundle\Entity\Kved $kveds)
    {
        $this->kveds[] = $kveds;

        return $this;
    }
    /**
     * Remove kveds
     *
     * @param \Lists\OrganizationBundle\Entity\Kved $kveds
     */
    public function removeKved (\Lists\OrganizationBundle\Entity\Kved $kveds)
    {
        $this->kveds->removeElement($kveds);
    }
    /**
     * Get kveds
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getKveds ()
    {
        return $this->kveds;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $services;

    /**
     * Add services
     *
     * @param \Lists\ProjectBundle\Entity\ServiceProjectStateTender $services
     * @return ProjectStateTender
     */
    public function addService (\Lists\ProjectBundle\Entity\ServiceProjectStateTender $services)
    {
        $this->services[] = $services;

        return $this;
    }
    /**
     * Remove services
     *
     * @param \Lists\ProjectBundle\Entity\ServiceProjectStateTender $services
     */
    public function removeService (\Lists\ProjectBundle\Entity\ServiceProjectStateTender $services)
    {
        $this->services->removeElement($services);
    }
    /**
     * Get services
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServices ()
    {
        return $this->services;
    }
    /**
     * getParticipations
     * 
     * @return mixed[]
     */
    public static function getParticipations ()
    {
        return array (
            true => 'Yes',
            false => 'No'
        );
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $participants;


    /**
     * Add participants
     *
     * @param \Lists\ProjectBundle\Entity\ProjectStateTenderParticipant $participants
     *
     * @return ProjectStateTender
     */
    public function addParticipan(\Lists\ProjectBundle\Entity\ProjectStateTenderParticipant $participants)
    {
        $this->participants[] = $participants;

        return $this;
    }

    /**
     * Remove participants
     *
     * @param \Lists\ProjectBundle\Entity\ProjectStateTenderParticipant $participants
     */
    public function removeParticipan(\Lists\ProjectBundle\Entity\ProjectStateTenderParticipant $participants)
    {
        $this->participants->removeElement($participants);
    }

    /**
     * Get participants
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParticipants()
    {
        return $this->participants;
    }
}