<?php

namespace SD\BusinessRoleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompanyClient
 *
 * @ORM\Table(name="CompanyClient")
 * @ORM\Entity
 */
class CompanyClient
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
}
