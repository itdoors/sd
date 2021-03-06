<?php

namespace Lists\OrganizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrganizationCurrentAccountType
 */
class OrganizationCurrentAccountType
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
    public function getId ()
    {
        return $this->id;
    }
    /**
     * Set name
     *
     * @param string $name
     *
     * @return OrganizationCurrentAccountType
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
     * __toString
     * 
     * @return string
     */
    public function __toString ()
    {
        return $this->getName();
    }
}
