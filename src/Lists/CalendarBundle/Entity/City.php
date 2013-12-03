<?php

namespace Lists\CityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * City
 */
class City
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;


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
     * Set name
     *
     * @param string $name
     * @return City
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $organizations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->organizations = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add organizations
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organizations
     * @return City
     */
    public function addOrganization(\Lists\OrganizationBundle\Entity\Organization $organizations)
    {
        $this->organizations[] = $organizations;
    
        return $this;
    }

    /**
     * Remove organizations
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organizations
     */
    public function removeOrganization(\Lists\OrganizationBundle\Entity\Organization $organizations)
    {
        $this->organizations->removeElement($organizations);
    }

    /**
     * Get organizations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrganizations()
    {
        return $this->organizations;
    }
    /**
     * @var \Lists\RegionBundle\Entity\Region
     */
    private $regions;


    /**
     * Set regions
     *
     * @param \Lists\RegionBundle\Entity\Region $regions
     * @return City
     */
    public function setRegions(\Lists\RegionBundle\Entity\Region $regions = null)
    {
        $this->regions = $regions;
    
        return $this;
    }

    /**
     * Get regions
     *
     * @return \Lists\RegionBundle\Entity\Region 
     */
    public function getRegions()
    {
        return $this->regions;
    }
    /**
     * @var \Lists\RegionBundle\Entity\Region
     */
    private $region;


    /**
     * Set region
     *
     * @param \Lists\RegionBundle\Entity\Region $region
     * @return City
     */
    public function setRegion(\Lists\RegionBundle\Entity\Region $region = null)
    {
        $this->region = $region;
    
        return $this;
    }

    /**
     * Get region
     *
     * @return \Lists\RegionBundle\Entity\Region 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * __toStrong
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}