<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrganizationGrantedForOrder
 *
 * @ORM\Table(name="organization_granted_for_order", options={
 *  "comment" = "Client can add claims only for these organizations/departments"
 *  })
 * @ORM\Entity
 */
class OrganizationGrantedForOrder
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     *
     * @ORM\ManyToOne(targetEntity="Lists\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id")
     */
    protected $organization;

    /**
     * @var \SD\BusinessRoleBundle\Entity\Client
     *
     * @ORM\ManyToOne(targetEntity="SD\BusinessRoleBundle\Entity\CompanyClient", inversedBy="grantedOrganizations")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    protected $companyClient;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Lists\DepartmentBundle\Entity\Departments")
     * @ORM\JoinTable(name="organization_granted_for_order_departments",
     *   joinColumns={@ORM\JoinColumn(name="org_granted_for_order_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="department_id", referencedColumnName="id")}
     *   )
     */
    protected $departments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->departments = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set organization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organization
     * 
     * @return OrganizationGrantedForOrder
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
     * Set companyClient
     *
     * @param \SD\BusinessRoleBundle\Entity\CompanyClient $companyClient
     * 
     * @return OrganizationGrantedForOrder
     */
    public function setCompanyClient(\SD\BusinessRoleBundle\Entity\CompanyClient $companyClient = null)
    {
        $this->companyClient = $companyClient;

        return $this;
    }

    /**
     * Get companyClient
     *
     * @return \SD\BusinessRoleBundle\Entity\CompanyClient 
     */
    public function getCompanyClient()
    {
        return $this->companyClient;
    }

    /**
     * Add department
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $department
     * 
     * @return OrganizationGrantedForOrder
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
}
