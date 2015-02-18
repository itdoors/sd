<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClaimDepartment
 *
 * @ORM\Entity
 */
class ClaimDepartment extends Claim
{
    /**
     * @var \Lists\DepartmentBundle\Entity\Departments
     *
     * @ORM\ManyToOne(targetEntity="Lists\DepartmentBundle\Entity\Departments", cascade="persist")
     * @ORM\JoinColumn(name="targetDepartment_id", referencedColumnName="id")
     */
    private $targetDepartment;



    /**
     * Set targetDepartment
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $targetDepartment
     * 
     * @return ClaimDepartment
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
