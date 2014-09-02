<?php

namespace Lists\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModelContactLevel
 */
class ModelContactLevel
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
     * @var integer
     */
    private $digit;


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
     * @return ModelContactLevel
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
     * Set digit
     *
     * @param integer $digit
     *
     * @return ModelContactLevel
     */
    public function setDigit($digit)
    {
        $this->digit = $digit;

        return $this;
    }

    /**
     * Get digit
     *
     * @return integer 
     */
    public function getDigit()
    {
        return $this->digit;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getDigit() . ' - ' . $this->getName();
    }
}
