<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClaimDep
 */
class ClaimDep extends Claim
{
    /**
     * @var \Lists\DepartmentBundle\Entity\Departments
     */
    protected $department;

    /**
     * Set department
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $department
     * 
     * @return ClaimDep
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
