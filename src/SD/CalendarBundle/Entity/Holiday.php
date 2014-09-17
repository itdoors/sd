<?php

namespace SD\CalendarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Holiday
 */
class Holiday
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $month;

    /**
     * @var integer
     */
    private $day;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $shortDescription;

    /**
     * @var string
     */
    private $description;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId ()
    {
        return $this->id;
    }
    /**
     * Set month
     *
     * @param integer $month
     * 
     * @return Holiday
     */
    public function setMonth ($month)
    {
        $this->month = $month;

        return $this;
    }
    /**
     * Get month
     *
     * @return integer 
     */
    public function getMonth ()
    {
        return $this->month;
    }
    /**
     * Set day
     *
     * @param integer $day
     * 
     * @return Holiday
     */
    public function setDay ($day)
    {
        $this->day = $day;

        return $this;
    }
    /**
     * Get day
     *
     * @return integer 
     */
    public function getDay ()
    {
        return $this->day;
    }
    /**
     * Set name
     *
     * @param string $name
     * 
     * @return Holiday
     */
    public function setName ($name)
    {
        $this->name = $name;

        return $this;
    }
    /**
     * Get name
     *
     * @return string 
     */
    public function getName ()
    {
        return $this->name;
    }
    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     * 
     * @return Holiday
     */
    public function setShortDescription ($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }
    /**
     * Get shortDescription
     *
     * @return string 
     */
    public function getShortDescription ()
    {
        return $this->shortDescription;
    }
    /**
     * Set description
     *
     * @param string $description
     * 
     * @return Holiday
     */
    public function setDescription ($description)
    {
        $this->description = $description;

        return $this;
    }
    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription ()
    {
        return $this->description;
    }
}
