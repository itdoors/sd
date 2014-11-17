<?php

namespace ITDoors\GeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * District
 */
class District
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
     * 
     * @return District
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
    private $cities;

    /**
     * @var \ITDoors\GeoBundle\Entity\Region
     */
    private $region;

    /**
     * Constructor
     * 
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->cities = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add city
     *
     * @param \ITDoors\GeoBundle\Entity\City $cities
     * 
     * @return District
     */
    public function addCity(\ITDoors\GeoBundle\Entity\City $cities)
    {
        $this->cities[] = $cities;

        return $this;
    }

    /**
     * Remove city
     *
     * @param \ITDoors\GeoBundle\Entity\City $city
     */
    public function removeCity(\ITDoors\GeoBundle\Entity\City $city)
    {
        $this->cities->removeElement($city);
    }

    /**
     * Get cities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * Set region
     *
     * @param \ITDoors\GeoBundle\Entity\Region $region
     * 
     * @return District
     */
    public function setRegion(\ITDoors\GeoBundle\Entity\Region $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \ITDoors\GeoBundle\Entity\Region 
     */
    public function getRegion()
    {
        return $this->region;
    }
}
