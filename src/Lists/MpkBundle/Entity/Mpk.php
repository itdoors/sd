<?php

namespace Lists\MpkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mpk
 */
class Mpk
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $active;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var \Lists\DepartmentBundle\Entity\Departments
     */
    private $department;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $organization;


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
     * Set name
     *
     * @param string $name
     * @return Mpk
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Mpk
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Mpk
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    
        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Mpk
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    
        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set department
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $department
     * @return Mpk
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

    /**
     * Set organization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organization
     * @return Mpk
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
     * __toString method
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
