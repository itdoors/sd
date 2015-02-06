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
    protected $targetDepartment;

    /**
     * Set targetDepartment
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $department
     * 
     * @return ClaimDep
     */
    public function setTargetDepartment(\Lists\DepartmentBundle\Entity\Departments $department = null)
    {
        $this->targetDepartment = $department;

        return $this;
    }

    /**
     * Get targetDepartment
     *
     * @return \Lists\DepartmentBundle\Entity\Departments 
     */
    public function getTargetDepartment()
    {
        return $this->targetDepartment;
    }
}
