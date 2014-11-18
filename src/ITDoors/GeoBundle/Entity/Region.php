<?php

namespace ITDoors\GeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Region
 */
class Region
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
     * @return Region
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $districts;

    /**
     * Constructor
     * 
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->cities = new \Doctrine\Common\Collections\ArrayCollection();
        $this->districts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add city
     *
     * @param \ITDoors\GeoBundle\Entity\City $city
     * 
     * @return Region
     */
    public function addCitie(\ITDoors\GeoBundle\Entity\City $city)
    {
        $this->cities[] = $city;

        return $this;
    }

    /**
     * Remove city
     *
     * @param \ITDoors\GeoBundle\Entity\City $city
     */
    public function removeCitie(\ITDoors\GeoBundle\Entity\City $city)
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
     * Add district
     *
     * @param \ITDoors\GeoBundle\Entity\District $district
     * 
     * @return Region
     */
    public function addDistrict(\ITDoors\GeoBundle\Entity\District $district)
    {
        $this->districts[] = $district;

        return $this;
    }

    /**
     * Remove districts
     *
     * @param \ITDoors\GeoBundle\Entity\District $district
     */
    public function removeDistrict(\ITDoors\GeoBundle\Entity\District $district)
    {
        $this->districts->removeElement($district);
    }

    /**
     * Get districts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDistricts()
    {
        return $this->districts;
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
