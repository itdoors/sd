<?php

namespace SD\BusinessRoleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompanyClient
 */
class CompanyClient extends Client
{
    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    protected $organization;

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
