<?php

namespace ITDoors\GeoBundle\Entity;

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
     * @var float
     */
    private $long;

    /**
     * @var float
     */
    private $lat;

    /**
     * Constructor
     * 
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
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
     * Set name
     *
     * @param string $name
     * 
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
     * Set long
     *
     * @param float $long
     * 
     * @return City
     */
    public function setLong($long)
    {
        $this->long = $long;

        return $this;
    }

    /**
     * Get long
     *
     * @return float 
     */
    public function getLong()
    {
        return $this->long;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * 
     * @return City
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @var \ITDoors\GeoBundle\Entity\Region
     */
    private $region;

    /**
     * @var \ITDoors\GeoBundle\Entity\District
     */
    private $district;


    /**
     * Set region
     *
     * @param \ITDoors\GeoBundle\Entity\Region $region
     * 
     * @return City
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

    /**
     * Set district
     *
     * @param \ITDoors\GeoBundle\Entity\District $district
     * 
     * @return City
     */
    public function setDistrict(\ITDoors\GeoBundle\Entity\District $district = null)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return \ITDoors\GeoBundle\Entity\District 
     */
    public function getDistrict()
    {
        return $this->district;
    }
}
