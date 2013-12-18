<?php

namespace Lists\OrganizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrganizationType
 */
class OrganizationType
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $title;


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
     * Set type
     *
     * @param string $type
     * @return OrganizationType
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return OrganizationType
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * __toStrong
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }
}