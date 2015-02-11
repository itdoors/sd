<?php

namespace SD\BusinessRoleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PersonClient
 *
 * @ORM\Entity
 */
class PersonClient extends Client
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $claims;

    /**
     * @var \Lists\IndividualBundle\Entity\Individual
     */
    private $individual;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->claims = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add claims
     *
     * @param \SD\ServiceDeskBundle\Entity\Claim $claims
     * @return PersonClient
     */
    public function addClaim(\SD\ServiceDeskBundle\Entity\Claim $claims)
    {
        $this->claims[] = $claims;
    
        return $this;
    }

    /**
     * Remove claims
     *
     * @param \SD\ServiceDeskBundle\Entity\Claim $claims
     */
    public function removeClaim(\SD\ServiceDeskBundle\Entity\Claim $claims)
    {
        $this->claims->removeElement($claims);
    }

    /**
     * Get claims
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClaims()
    {
        return $this->claims;
    }

    /**
     * Set individual
     *
     * @param \Lists\IndividualBundle\Entity\Individual $individual
     * @return PersonClient
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