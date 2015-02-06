<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StateTender
 */
class StateTender extends Project
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
     * @var float
     */
    private $pf;

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
    }
    /**
     * Set vdz
     *
     * @param string $vdz
     * @return StateTender
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
     * @return StateTender
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
     * @return StateTender
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
     * @return StateTender
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
     * @return StateTender
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
     * @return StateTender
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
     * @return StateTender
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
     * @return StateTender
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
     * @return StateTender
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
     * @return StateTender
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
     * @return StateTender
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
     * Set pf
     *
     * @param float $pf
     * @return StateTender
     */
    public function setPf ($pf)
    {
        $this->pf = $pf;

        return $this;
    }
    /**
     * Get pf
     *
     * @return float 
     */
    public function getPf ()
    {
        return $this->pf;
    }
    /**
     * Add kveds
     *
     * @param \Lists\OrganizationBundle\Entity\Kved $kveds
     *
     * @return StateTender
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
     * @param \Lists\ProjectBundle\Entity\ServiceStateTender $services
     * @return StateTender
     */
    public function addService (\Lists\ProjectBundle\Entity\ServiceStateTender $services)
    {
        $this->services[] = $services;

        return $this;
    }
    /**
     * Remove services
     *
     * @param \Lists\ProjectBundle\Entity\ServiceStateTender $services
     */
    public function removeService (\Lists\ProjectBundle\Entity\ServiceStateTender $services)
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
     * @var \Lists\ProjectBundle\Entity\StatusStateTender
     */
    private $statusStateTender;

    /**
     * Set statusStateTender
     *
     * @param \Lists\ProjectBundle\Entity\StatusStateTender $statusStateTender
     *
     * @return StateTender
     */
    public function setStatusStateTender (\Lists\ProjectBundle\Entity\StatusStateTender $statusStateTender = null)
    {
        $this->statusStateTender = $statusStateTender;

        return $this;
    }
    /**
     * Get statusStateTender
     *
     * @return \Lists\ProjectBundle\Entity\StatusStateTender 
     */
    public function getStatusStateTender ()
    {
        return $this->statusStateTender;
    }
}
