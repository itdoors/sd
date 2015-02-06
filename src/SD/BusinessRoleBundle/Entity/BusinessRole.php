<?php

namespace SD\BusinessRoleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BusinessRole
 */
class BusinessRole
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Lists\IndividualBundle\Entity\Individual
     */
    protected $individual;


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
     * Set individual
     *
     * @param \Lists\IndividualBundle\Entity\Individual $individual
     * 
     * @return BusinessRole
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
