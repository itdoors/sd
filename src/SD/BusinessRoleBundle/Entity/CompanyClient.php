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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="SD\ServiceDeskBundle\Entity\OrganizationGrantedForOrder", mappedBy="companyClient")
     */
    protected $grantedOrganizations;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Lists\OrganizationBundle\Entity\Organization")
     * @ORM\JoinTable(name="origin_organization_for_client",
     *      joinColumns={@ORM\JoinColumn(name="client_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="organization_id", referencedColumnName="id")}
     *      )
     */
    protected $originOrganizations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->grantedOrganizations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->originOrganizations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add grantedOrganization
     *
     * @param \SD\ServiceDeskBundle\Entity\OrganizationGrantedForOrder $grantedOrganization
     * 
     * @return CompanyClient
     */
    public function addGrantedOrganization(\SD\ServiceDeskBundle\Entity\OrganizationGrantedForOrder $grantedOrganization)
    {
        $this->grantedOrganizations[] = $grantedOrganization;

        return $this;
    }

    /**
     * Remove grantedOrganization
     *
     * @param \SD\ServiceDeskBundle\Entity\OrganizationGrantedForOrder $grantedOrganization
     */
    public function removeGrantedOrganization(\SD\ServiceDeskBundle\Entity\OrganizationGrantedForOrder $grantedOrganization)
    {
        $this->grantedOrganizations->removeElement($grantedOrganization);
    }

    /**
     * Get grantedOrganizations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGrantedOrganizations()
    {
        return $this->grantedOrganizations;
    }

    /**
     * Add originOrganization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $originOrganization
     * 
     * @return CompanyClient
     */
    public function addOriginOrganization(\Lists\OrganizationBundle\Entity\Organization $originOrganization)
    {
        $this->originOrganizations[] = $originOrganization;

        return $this;
    }

    /**
     * Remove originOrganization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $originOrganization
     */
    public function removeOriginOrganization(\Lists\OrganizationBundle\Entity\Organization $originOrganization)
    {
        $this->originOrganizations->removeElement($originOrganization);
    }

    /**
     * Get originOrganizations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOriginOrganizations()
    {
        return $this->originOrganizations;
    }
}
