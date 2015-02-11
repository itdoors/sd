<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectCommercialTender
 */
class ProjectCommercialTender extends Project
{

    public function getDiscr()
    {
        return 'commercial_tender';
    }
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
     * @return ProjectСommercialTender
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
     * @return ProjectСommercialTender
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
        parent::__construct();
        $this->services = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add services
     *
     * @param \Lists\ProjectBundle\Entity\ServiceProjectSimple $services
     * @return ProjectСommercialTender
     */
    public function addService(\Lists\ProjectBundle\Entity\ServiceProjectSimple $services)
    {
        $this->services[] = $services;
    
        return $this;
    }

    /**
     * Remove services
     *
     * @param \Lists\ProjectBundle\Entity\ServiceProjectSimple $services
     */
    public function removeService(\Lists\ProjectBundle\Entity\ServiceProjectSimple $services)
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
    /**
     * getProjectType
     * 
     * @return string
     */
    public function getProjectType()
    {
        return 'Commercial tender';
    }
}