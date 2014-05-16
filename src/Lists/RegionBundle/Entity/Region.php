<?php

namespace Lists\RegionBundle\Entity;

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
     * @var float
     */
    private $square;

    /**
     * @var float
     */
    private $population;

    /**
     * @var string
     */
    private $flag;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $cities;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cities = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set square
     *
     * @param float $square
     * @return Region
     */
    public function setSquare($square)
    {
        $this->square = $square;

        return $this;
    }

    /**
     * Get square
     *
     * @return float 
     */
    public function getSquare()
    {
        return $this->square;
    }

    /**
     * Set population
     *
     * @param float $population
     * @return Region
     */
    public function setPopulation($population)
    {
        $this->population = $population;

        return $this;
    }

    /**
     * Get population
     *
     * @return float 
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * Set flag
     *
     * @param string $flag
     * @return Region
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get flag
     *
     * @return string 
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Add cities
     *
     * @param \Lists\CityBundle\Entity\City $cities
     * @return Region
     */
    public function addCitie(\Lists\CityBundle\Entity\City $cities)
    {
        $this->cities[] = $cities;

        return $this;
    }

    /**
     * Remove cities
     *
     * @param \Lists\CityBundle\Entity\City $cities
     */
    public function removeCitie(\Lists\CityBundle\Entity\City $cities)
    {
        $this->cities->removeElement($cities);
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $companystructure;

    /**
     * Add companystructure
     *
     * @param \Lists\CompanystructureBundle\Entity\Companystructure $companystructure
     * @return Region
     */
    public function addCompanystructure(\Lists\CompanystructureBundle\Entity\Companystructure $companystructure)
    {
        $this->companystructure[] = $companystructure;

        return $this;
    }

    /**
     * Remove companystructure
     *
     * @param \Lists\CompanystructureBundle\Entity\Companystructure $companystructure
     */
    public function removeCompanystructure(\Lists\CompanystructureBundle\Entity\Companystructure $companystructure)
    {
        $this->companystructure->removeElement($companystructure);
    }

    /**
     * Get companystructure
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCompanystructure()
    {
        return $this->companystructure;
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