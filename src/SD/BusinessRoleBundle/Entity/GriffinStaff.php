<?php

namespace SD\BusinessRoleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GriffinStaff
 *
 * @ORM\Entity
 */
class GriffinStaff extends Staff
{
    /**
     * @var \Lists\DepartmentBundle\Entity\Departments
     *
     * @ORM\ManyToOne(targetEntity="Lists\CompanystructureBundle\Entity\Companystructure")
     * @ORM\JoinColumn(name="companystructure_id", referencedColumnName="id")
     */
    protected $companystructure;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->responsibilities = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set companystructure
     *
     * @param \Lists\CompanystructureBundle\Entity\Companystructure $companystructure
     * 
     * @return GriffinStaff
     */
    public function setCompanystructure(\Lists\CompanystructureBundle\Entity\Companystructure $companystructure = null)
    {
        $this->companystructure = $companystructure;

        return $this;
    }

    /**
     * Get companystructure
     *
     * @return \Lists\CompanystructureBundle\Entity\Companystructure 
     */
    public function getCompanystructure()
    {
        return $this->companystructure;
    }
}
