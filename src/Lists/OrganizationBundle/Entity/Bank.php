<?php

namespace Lists\OrganizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bank
 */
class Bank
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
    private $mfo;

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
     * @return Bank
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
     * Set mfo
     *
     * @param string $mfo
     *
     * @return Bank
     */
    public function setMfo ($mfo)
    {
        $this->mfo = $mfo;

        return $this;
    }
    /**
     * Get mfo
     *
     * @return string 
     */
    public function getMfo ()
    {
        return $this->mfo;
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
