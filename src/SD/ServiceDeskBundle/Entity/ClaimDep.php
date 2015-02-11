<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClaimDep
 *
 * @ORM\Entity
 */
class ClaimDep extends Claim
{
    /**
     * @var \Lists\DepartmentBundle\Entity\Departments
     *
     * @ORM\ManyToOne(targetEntity="Lists\DepartmentBundle\Entity\Departments")
     * @ORM\JoinColumn(name="targetDepartment_id", referencedColumnName="id")
     */
    private $targetDepartment;



    /**
     * Set targetDepartment
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $targetDepartment
     * 
     * @return ClaimDep
     */
    public function setTargetDepartment(\Lists\DepartmentBundle\Entity\Departments $targetDepartment = null)
    {
        $this->targetDepartment = $targetDepartment;

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
