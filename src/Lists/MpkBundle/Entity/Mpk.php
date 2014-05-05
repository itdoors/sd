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
     * @var \Lists\DepartmentBundle\Entity\Departments
     */
    private $department;


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
     * toString Method
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}