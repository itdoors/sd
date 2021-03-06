<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectGosTender
 */
class ProjectGosTender
{
    /**
     * __toString
     * 
     * @return string
     */
    public function __toString ()
    {
        return (string) $this->getAdvert();
    }
    /**
     * @var integer
     */
    private $id;

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
    private $reason;

    /**
     * @var \DateTime
     */
    private $datetimeDeleted;

    /**
     * @var \Lists\HandlingBundle\Entity\Handling
     */
    private $project;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId ()
    {
        return $this->id;
    }
    /**
     * Set vdz
     *
     * @param string $vdz
     *
     * @return ProjectGosTender
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
     * @return ProjectGosTender
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
     * @return ProjectGosTender
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
     * @return ProjectGosTender
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
     * @return ProjectGosTender
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
     * @return ProjectGosTender
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
     * @return ProjectGosTender
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
     * @return ProjectGosTender
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
     * Set reason
     *
     * @param text $reason
     *
     * @return ProjectGosTender
     */
    public function setReason ($reason)
    {
        $this->reason = $reason;

        return $this;
    }
    /**
     * Get reason
     *
     * @return text 
     */
    public function getReason ()
    {
        return $this->reason;
    }
    /**
     * Set datetimeDeleted
     *
     * @param \DateTime $datetimeDeleted
     *
     * @return ProjectGosTender
     */
    public function setDatetimeDeleted ($datetimeDeleted)
    {
        $this->datetimeDeleted = $datetimeDeleted;

        return $this;
    }
    /**
     * Get datetimeDeleted
     *
     * @return \DateTime 
     */
    public function getDatetimeDeleted ()
    {
        return $this->datetimeDeleted;
    }
    /**
     * Set project
     *
     * @param \Lists\HandlingBundle\Entity\Handling $project
     *
     * @return ProjectGosTender
     */
    public function setProject (\Lists\HandlingBundle\Entity\Handling $project = null)
    {
        $this->project = $project;

        return $this;
    }
    /**
     * Get project
     *
     * @return \Lists\HandlingBundle\Entity\Handling 
     */
    public function getProject ()
    {
        return $this->project;
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
    private $kveds;

    /**
     * Add kveds
     *
     * @param \Lists\OrganizationBundle\Entity\Kved $kveds
     * @return ProjectGosTender
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
     * Constructor
     */
    public function __construct ()
    {
        $this->kveds = new \Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * @var boolean
     */
    private $isParticipation;

    /**
     * Set isParticipation
     *
     * @param boolean $isParticipation
     *
     * @return ProjectGosTender
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $participans;

    /**
     * Add participans
     *
     * @param \Lists\HandlingBundle\Entity\ProjectGosTenderParticipan $participans
     *
     * @return ProjectGosTender
     */
    public function addParticipan(\Lists\HandlingBundle\Entity\ProjectGosTenderParticipan $participans)
    {
        $this->participans[] = $participans;

        return $this;
    }

    /**
     * Remove participans
     *
     * @param \Lists\HandlingBundle\Entity\ProjectGosTenderParticipan $participans
     */
    public function removeParticipan(\Lists\HandlingBundle\Entity\ProjectGosTenderParticipan $participans)
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
}
