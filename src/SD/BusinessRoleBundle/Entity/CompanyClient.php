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
     * @ORM\ManyToMany(targetEntity="Lists\OrganizationBundle\Entity\Organization")
     * @ORM\JoinTable(name="granted_organizations_for_client",
     *   joinColumns={@ORM\JoinColumn(name="client_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="organization_id", referencedColumnName="id")}
     *   )
     */
    protected $grantedOrganizations;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Lists\DepartmentBundle\Entity\Departments")
     * @ORM\JoinTable(name="client_departments_for_order",
     *   joinColumns={@ORM\JoinColumn(name="client_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="department_id", referencedColumnName="id")}
     *   )
     */
    protected $departments;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Lists\OrganizationBundle\Entity\Organization")
     * @ORM\JoinTable(name="origin_organizations_for_client",
     *   joinColumns={@ORM\JoinColumn(name="client_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="organization_id", referencedColumnName="id")}
     *   )
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

    /**
     * Add department
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $department
     * 
     * @return CompanyClient
     */
    public function addDepartment(\Lists\DepartmentBundle\Entity\Departments $department)
    {
        $this->departments[] = $department;

        return $this;
    }

    /**
     * Remove department
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $department
     */
    public function removeDepartment(\Lists\DepartmentBundle\Entity\Departments $department)
    {
        $this->departments->removeElement($department);
    }

    /**
     * Get departments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDepartments()
    {
        return $this->departments;
    }

    /**
     * Add claim
     *
     * @param \SD\ServiceDeskBundle\Entity\Claim $claim
     * 
     * @return CompanyClient
     */
    public function addClaim(\SD\ServiceDeskBundle\Entity\Claim $claim)
    {
        $this->claims[] = $claim;

        return $this;
    }

    /**
     * Remove claim
     *
     * @param \SD\ServiceDeskBundle\Entity\Claim $claim
     */
    public function removeClaim(\SD\ServiceDeskBundle\Entity\Claim $claim)
    {
        $this->claims->removeElement($claim);
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
}
