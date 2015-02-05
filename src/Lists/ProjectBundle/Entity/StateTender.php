<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StateTender
 */
class StateTender
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
    private $participans;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $kveds;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->participans = new \Doctrine\Common\Collections\ArrayCollection();
        $this->kveds = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set vdz
     *
     * @param string $vdz
     * @return StateTender
     */
    public function setVdz($vdz)
    {
        $this->vdz = $vdz;
    
        return $this;
    }

    /**
     * Get vdz
     *
     * @return string 
     */
    public function getVdz()
    {
        return $this->vdz;
    }

    /**
     * Set advert
     *
     * @param integer $advert
     * @return StateTender
     */
    public function setAdvert($advert)
    {
        $this->advert = $advert;
    
        return $this;
    }

    /**
     * Get advert
     *
     * @return integer 
     */
    public function getAdvert()
    {
        return $this->advert;
    }

    /**
     * Set typeOfProcedure
     *
     * @param string $typeOfProcedure
     * @return StateTender
     */
    public function setTypeOfProcedure($typeOfProcedure)
    {
        $this->typeOfProcedure = $typeOfProcedure;
    
        return $this;
    }

    /**
     * Get typeOfProcedure
     *
     * @return string 
     */
    public function getTypeOfProcedure()
    {
        return $this->typeOfProcedure;
    }

    /**
     * Set place
     *
     * @param string $place
     * @return StateTender
     */
    public function setPlace($place)
    {
        $this->place = $place;
    
        return $this;
    }

    /**
     * Get place
     *
     * @return string 
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set delivery
     *
     * @param string $delivery
     * @return StateTender
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;
    
        return $this;
    }

    /**
     * Get delivery
     *
     * @return string 
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * Set datetimeDeadline
     *
     * @param \DateTime $datetimeDeadline
     * @return StateTender
     */
    public function setDatetimeDeadline($datetimeDeadline)
    {
        $this->datetimeDeadline = $datetimeDeadline;
    
        return $this;
    }

    /**
     * Get datetimeDeadline
     *
     * @return \DateTime 
     */
    public function getDatetimeDeadline()
    {
        return $this->datetimeDeadline;
    }

    /**
     * Set datetimeOpening
     *
     * @param \DateTime $datetimeOpening
     * @return StateTender
     */
    public function setDatetimeOpening($datetimeOpening)
    {
        $this->datetimeOpening = $datetimeOpening;
    
        return $this;
    }

    /**
     * Get datetimeOpening
     *
     * @return \DateTime 
     */
    public function getDatetimeOpening()
    {
        return $this->datetimeOpening;
    }

    /**
     * Set software
     *
     * @param string $software
     * @return StateTender
     */
    public function setSoftware($software)
    {
        $this->software = $software;
    
        return $this;
    }

    /**
     * Get software
     *
     * @return string 
     */
    public function getSoftware()
    {
        return $this->software;
    }

    /**
     * Set isParticipation
     *
     * @param boolean $isParticipation
     * @return StateTender
     */
    public function setIsParticipation($isParticipation)
    {
        $this->isParticipation = $isParticipation;
    
        return $this;
    }

    /**
     * Get isParticipation
     *
     * @return boolean 
     */
    public function getIsParticipation()
    {
        return $this->isParticipation;
    }

    /**
     * Set reason
     *
     * @param string $reason
     * @return StateTender
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
     * Set budget
     *
     * @param string $budget
     * @return StateTender
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
     * Add participans
     *
     * @param \Lists\ProjectBundle\Entity\ProjectGosTenderParticipan $participans
     * @return StateTender
     */
    public function addParticipan(\Lists\ProjectBundle\Entity\ProjectGosTenderParticipan $participans)
    {
        $this->participans[] = $participans;
    
        return $this;
    }

    /**
     * Remove participans
     *
     * @param \Lists\ProjectBundle\Entity\ProjectGosTenderParticipan $participans
     */
    public function removeParticipan(\Lists\ProjectBundle\Entity\ProjectGosTenderParticipan $participans)
    {
        $this->participans->removeElement($participans);
    }

    /**
     * Get participans
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParticipans()
    {
        return $this->participans;
    }

    /**
     * Add kveds
     *
     * @param \Lists\OrganizationBundle\Entity\Kved $kveds
     * @return StateTender
     */
    public function addKved(\Lists\OrganizationBundle\Entity\Kved $kveds)
    {
        $this->kveds[] = $kveds;
    
        return $this;
    }

    /**
     * Remove kveds
     *
     * @param \Lists\OrganizationBundle\Entity\Kved $kveds
     */
    public function removeKved(\Lists\OrganizationBundle\Entity\Kved $kveds)
    {
        $this->kveds->removeElement($kveds);
    }

    /**
     * Get kveds
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getKveds()
    {
        return $this->kveds;
    }
}