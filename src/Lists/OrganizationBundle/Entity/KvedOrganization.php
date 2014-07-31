<?php

namespace Lists\OrganizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * KvedOrganization
 */
class KvedOrganization
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $organization;

    /**
     * @var \Lists\OrganizationBundle\Entity\Kved
     */
    private $kved;


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
     * Set organization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organization
     * @return KvedOrganization
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
     * Set kved
     *
     * @param \Lists\OrganizationBundle\Entity\Kved $kved
     * @return KvedOrganization
     */
    public function setKved(\Lists\OrganizationBundle\Entity\Kved $kved = null)
    {
        $this->kved = $kved;
    
        return $this;
    }

    /**
     * Get kved
     *
     * @return \Lists\OrganizationBundle\Entity\Kved 
     */
    public function getKved()
    {
        return $this->kved;
    }
    /**
     * @ORM\PrePersist
     */
    public function doOnPrePersist()
    {
        // Add your code here
    }
}