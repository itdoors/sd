<?php

namespace Lists\IndividualBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contact
 */
class Contact
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var contactType
     */
    private $type;

    /**
     * @var string
     */
    private $value;

    /**
     * @var \Lists\IndividualBundle\Entity\Individual
     */
    private $individual;

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
     * @param contactType $type
     * 
     * @return Contact
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return contactType 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set value
     *
     * @param string $value
     * 
     * @return Contact
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set individual
     *
     * @param \Lists\IndividualBundle\Entity\Individual $individual
     * 
     * @return Contact
     */
    public function setIndividual(\Lists\IndividualBundle\Entity\Individual $individual = null)
    {
        $this->individual = $individual;

        return $this;
    }

    /**
     * Get individual
     *
     * @return \Lists\IndividualBundle\Entity\Individual 
     */
    public function getIndividual()
    {
        return $this->individual;
    }
}
