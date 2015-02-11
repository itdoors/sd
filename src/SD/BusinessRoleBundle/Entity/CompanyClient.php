<?php

namespace SD\BusinessRoleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompanyClient
 *
 * @ORM\Entity
 */
class CompanyClient extends Client
{
    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     *
     * @ORM\ManyToOne(targetEntity="Lists\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id")
     */
    private $organization;

    /**
     * Set organization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organization
     * 
     * @return CompanyClient
     */
    public function setOrganization(\Lists\OrganizationBundle\Entity\Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return \Lists\OrganizationBundle\Entity\Organization 
     */
    public function getOrganization()
    {
        return $this->organization;
    }
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
     * @return CompanyClient
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
     * @return CompanyClient
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