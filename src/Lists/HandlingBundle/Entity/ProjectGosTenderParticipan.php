<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectGosTenderParticipan
 */
class ProjectGosTenderParticipan
{
    /**
     * __construct
     */
    public function __construct ()
    {
        $this->datetimeCreate = new \DateTime();
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
    private $datetimeDeleted;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $participan;

    /**
     * @var \Lists\HandlingBundle\Entity\ProjectGosTender
     */
    private $gosTender;


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
     * @return ProjectGosTenderParticipan
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
     *
     * @return ProjectGosTenderParticipan
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
     *
     * @return ProjectGosTenderParticipan
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
     * Set datetimeDeleted
     *
     * @param \DateTime $datetimeDeleted
     *
     * @return ProjectGosTenderParticipan
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
     * Set participan
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $participan
     *
     * @return ProjectGosTenderParticipan
     */
    public function setParticipan(\Lists\OrganizationBundle\Entity\Organization $participan = null)
    {
        $this->participan = $participan;

        return $this;
    }

    /**
     * Get participan
     *
     * @return \Lists\OrganizationBundle\Entity\Organization 
     */
    public function getParticipan()
    {
        return $this->participan;
    }

    /**
     * Set gosTender
     *
     * @param \Lists\HandlingBundle\Entity\ProjectGosTender $gosTender
     *
     * @return ProjectGosTenderParticipan
     */
    public function setGosTender(\Lists\HandlingBundle\Entity\ProjectGosTender $gosTender = null)
    {
        $this->gosTender = $gosTender;

        return $this;
    }

    /**
     * Get gosTender
     *
     * @return \Lists\HandlingBundle\Entity\ProjectGosTender 
     */
    public function getGosTender()
    {
        return $this->gosTender;
    }
    /**
     * @var \DateTime
     */
    private $datetimeCreate;


    /**
     * Set datetimeCreate
     *
     * @param \DateTime $datetimeCreate
     * @return ProjectGosTenderParticipan
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
}
