<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectElectronicTrading
 */
class ProjectElectronicTrading extends Project
{

    public function getDiscr()
    {
        return 'electronic_trading';
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
     * @return ProjectSimple
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
        return 'Electronic trading';
    }
    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $xz;


    /**
     * Set xz
     *
     * @param \SD\UserBundle\Entity\User $xz
     * @return ProjectElectronicTrading
     */
    public function setXz(\SD\UserBundle\Entity\User $xz = null)
    {
        $this->xz = $xz;
    
        return $this;
    }

    /**
     * Get xz
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getXz()
    {
        return $this->xz;
    }
}