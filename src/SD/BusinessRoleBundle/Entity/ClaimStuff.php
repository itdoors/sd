<?php

namespace SD\BusinessRoleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClaimStuff
 *
 * @ORM\Entity
 */
class ClaimStuff extends Stuff
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $responsibilities;

    /**
     * @var \Lists\IndividualBundle\Entity\Individual
     */
    private $individual;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->responsibilities = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Add responsibilities
     *
     * @param \SD\BusinessRoleBundle\Entity\Responsibility $responsibilities
     * @return ClaimStuff
     */
    public function addResponsibilitie(\SD\BusinessRoleBundle\Entity\Responsibility $responsibilities)
    {
        $this->responsibilities[] = $responsibilities;
    
        return $this;
    }

    /**
     * Remove responsibilities
     *
     * @param \SD\BusinessRoleBundle\Entity\Responsibility $responsibilities
     */
    public function removeResponsibilitie(\SD\BusinessRoleBundle\Entity\Responsibility $responsibilities)
    {
        $this->responsibilities->removeElement($responsibilities);
    }

    /**
     * Get responsibilities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResponsibilities()
    {
        return $this->responsibilities;
    }

    /**
     * Set individual
     *
     * @param \Lists\IndividualBundle\Entity\Individual $individual
     * @return ClaimStuff
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