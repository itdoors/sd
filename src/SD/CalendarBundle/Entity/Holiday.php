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

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * Set date
     *
     * @param \DateTime $date
     * 
     * @return Holiday
     */
    public function setDate ($date)
    {
        $this->date = $date;

        return $this;
    }
    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate ()
    {
        return $this->date;
    }
}
