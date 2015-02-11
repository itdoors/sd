<?php

namespace SD\BusinessRoleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClaimResponsibility
 *
 * @ORM\Entity
 */
class ClaimResponsibility extends Responsibility
{
    /**
     * @var \Lists\DepartmentBundle\Entity\Departments
     *
     * @ORM\ManyToOne(targetEntity="Lists\DepartmentBundle\Entity\Departments")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     */
    protected $department;

    /**
     * @var array
     *
     * @ORM\Column(name="claimTypes", type="array")
     */
    protected $claimTypes;

    /**
     * Set claimTypes
     *
     * @param array $claimTypes
     * 
     * @return ClaimResponsibility
     */
    public function setClaimTypes($claimTypes)
    {
        $this->claimTypes = $claimTypes;

        return $this;
    }

    /**
     * Get claimTypes
     *
     * @return array 
     */
    public function getClaimTypes()
    {
        return $this->claimTypes;
    }

    /**
     * Set department
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $department
     * 
     * @return ClaimResponsibility
     */
    public function setDepartment(\Lists\DepartmentBundle\Entity\Departments $department = null)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return \Lists\DepartmentBundle\Entity\Departments 
     */
    public function getDepartment()
    {
        return $this->department;
    }
}
