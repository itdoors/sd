<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * СommercialTender
 */
class СommercialTender extends Project
{

    /**
     * @var \DateTime
     */
    private $datetimeDeadline;

    /**
     * @var \DateTime
     */
    private $datetimeOpening;

    /**
     * Set datetimeDeadline
     *
     * @param \DateTime $datetimeDeadline
     *
     * @return СommercialTender
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
     * @return СommercialTender
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $services;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->services = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add services
     *
     * @param \Lists\ProjectBundle\Entity\ServiceСommercialTender $services
     * @return СommercialTender
     */
    public function addService(\Lists\ProjectBundle\Entity\ServiceСommercialTender $services)
    {
        $this->services[] = $services;
    
        return $this;
    }

    /**
     * Remove services
     *
     * @param \Lists\ProjectBundle\Entity\ServiceСommercialTender $services
     */
    public function removeService(\Lists\ProjectBundle\Entity\ServiceСommercialTender $services)
    {
        $this->services->removeElement($services);
    }

    /**
     * Get services
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServices()
    {
        return $this->services;
    }
}