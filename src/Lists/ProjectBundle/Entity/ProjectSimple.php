<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectSimple
 */
class ProjectSimple extends Project
{

    public function getDiscr()
    {
        return 'simple';
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
        return 'Negotiation procedure';
    }
      /**
     * getCoiceConfirm
     * 
     * @return mixed[]
     */
    public static function getListDiscr ()
    {
        return array (
            'project_commercial_tender' => 'Commercial tender',
            'project_electronic_trading' => 'Electronic trading'
        );
    }
}