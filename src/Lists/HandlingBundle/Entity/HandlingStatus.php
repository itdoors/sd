<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HandlingStatus
 */
class HandlingStatus
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
     * @return HandlingStatus
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
     * __toStrong
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
    /**
     * @var integer
     */
    private $sortorder;


    /**
     * Set sortorder
     *
     * @param integer $sortorder
     * @return HandlingStatus
     */
    public function setSortorder($sortorder)
    {
        $this->sortorder = $sortorder;
    
        return $this;
    }

    /**
     * Get sortorder
     *
     * @return integer 
     */
    public function getSortorder()
    {
        return $this->sortorder;
    }
    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $percentageString;

    /**
     * @var integer
     */
    private $progress;


    /**
     * Set slug
     *
     * @param string $slug
     * @return HandlingStatus
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set percentageString
     *
     * @param string $percentageString
     * @return HandlingStatus
     */
    public function setPercentageString($percentageString)
    {
        $this->percentageString = $percentageString;
    
        return $this;
    }

    /**
     * Get percentageString
     *
     * @return string 
     */
    public function getPercentageString()
    {
        return $this->percentageString;
    }

    /**
     * Set progress
     *
     * @param integer $progress
     * @return HandlingStatus
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;
    
        return $this;
    }

    /**
     * Get progress
     *
     * @return integer 
     */
    public function getProgress()
    {
        return $this->progress;
    }
}