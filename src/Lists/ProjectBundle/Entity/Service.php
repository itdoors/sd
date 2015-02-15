<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 */
class Service
{
    public function __toString ()
    {
        return $this->getName();
    }
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
    private $slug;

    /**
     * @var integer
     */
    private $sortorder;

    /**
     * @var integer
     */
    private $reportNumber;
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
     * @return Service
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Service
     */
    public function setSlug ($slug)
    {
        $this->slug = $slug;

        return $this;
    }
    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug ()
    {
        return $this->slug;
    }
    /**
     * Set sortorder
     *
     * @param integer $sortorder
     *
     * @return Service
     */
    public function setSortorder ($sortorder)
    {
        $this->sortorder = $sortorder;

        return $this;
    }
    /**
     * Get sortorder
     *
     * @return integer 
     */
    public function getSortorder ()
    {
        return $this->sortorder;
    }
    /**
     * Set reportNumber
     *
     * @param integer $reportNumber
     *
     * @return Service
     */
    public function setReportNumber ($reportNumber)
    {
        $this->reportNumber = $reportNumber;

        return $this;
    }
    /**
     * Get reportNumber
     *
     * @return integer 
     */
    public function getReportNumber ()
    {
        return $this->reportNumber;
    }
}