<?php

namespace Lists\CoachBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CoachRegion
 */
class CoachRegion
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $regions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->regions = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * 
     * @return CoachRegion
     */
    public function setUser(\SD\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add region
     *
     * @param \Lists\RegionBundle\Entity\Region $region
     * 
     * @return CoachRegion
     */
    public function addRegion(\Lists\RegionBundle\Entity\Region $region)
    {
        $this->regions[] = $region;

        return $this;
    }

    /**
     * Remove region
     *
     * @param \Lists\RegionBundle\Entity\Region $region
     */
    public function removeRegion(\Lists\RegionBundle\Entity\Region $region)
    {
        $this->regions->removeElement($region);
    }

    /**
     * Get regions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRegions()
    {
        return $this->regions;
    }

    /**
     * Set regions
     *
     * @param \Doctrine\Common\Collections\Collection $regions
     */
    public function setRegions(\Doctrine\Common\Collections\Collection $regions)
    {
        $this->regions = $regions;
    }
}
