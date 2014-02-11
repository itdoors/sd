<?php

namespace Lists\DogovorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DogovorDepartment
 */
class DogovorDepartment
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $isActive;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var \DateTime
     */
    private $createdatetime;

    /**
     * @var \Lists\DepartmentBundle\Entity\Departments
     */
    private $department;

    /**
     * @var \Lists\DogovorBundle\Entity\Dogovor
     */
    private $dogovor;

    /**
     * @var \Lists\DogovorBundle\Entity\DopDogovor
     */
    private $dopDogovor;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $user;


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
     * Set isActive
     *
     * @param boolean $isActive
     * @return DogovorDepartment
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return DogovorDepartment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set createdatetime
     *
     * @param \DateTime $createdatetime
     * @return DogovorDepartment
     */
    public function setCreatedatetime($createdatetime)
    {
        $this->createdatetime = $createdatetime;
    
        return $this;
    }

    /**
     * Get createdatetime
     *
     * @return \DateTime 
     */
    public function getCreatedatetime()
    {
        return $this->createdatetime;
    }

    /**
     * Set department
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $department
     * @return DogovorDepartment
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
     * Set dogovor
     *
     * @param \Lists\DogovorBundle\Entity\Dogovor $dogovor
     * @return DogovorDepartment
     */
    public function setDogovor(\Lists\DogovorBundle\Entity\Dogovor $dogovor = null)
    {
        $this->dogovor = $dogovor;
    
        return $this;
    }

    /**
     * Get dogovor
     *
     * @return \Lists\DogovorBundle\Entity\Dogovor 
     */
    public function getDogovor()
    {
        return $this->dogovor;
    }

    /**
     * Set dopDogovor
     *
     * @param \Lists\DogovorBundle\Entity\DopDogovor $dopDogovor
     * @return DogovorDepartment
     */
    public function setDopDogovor(\Lists\DogovorBundle\Entity\DopDogovor $dopDogovor = null)
    {
        $this->dopDogovor = $dopDogovor;
    
        return $this;
    }

    /**
     * Get dopDogovor
     *
     * @return \Lists\DogovorBundle\Entity\DopDogovor 
     */
    public function getDopDogovor()
    {
        return $this->dopDogovor;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * @return DogovorDepartment
     */
    public function setUser(\SD\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @var integer
     */
    private $dogovorId;


    /**
     * Set dogovorId
     *
     * @param integer $dogovorId
     * @return DogovorDepartment
     */
    public function setDogovorId($dogovorId)
    {
        $this->dogovorId = $dogovorId;
    
        return $this;
    }

    /**
     * Get dogovorId
     *
     * @return integer 
     */
    public function getDogovorId()
    {
        return $this->dogovorId;
    }
    /**
     * @var integer
     */
    private $dopDogovorId;


    /**
     * Set dopDogovorId
     *
     * @param integer $dopDogovorId
     * @return DogovorDepartment
     */
    public function setDopDogovorId($dopDogovorId)
    {
        $this->dopDogovorId = $dopDogovorId;
    
        return $this;
    }

    /**
     * Get dopDogovorId
     *
     * @return integer 
     */
    public function getDopDogovorId()
    {
        return $this->dopDogovorId;
    }
    /**
     * @var integer
     */
    private $departmentId;


    /**
     * Set departmentId
     *
     * @param integer $departmentId
     * @return DogovorDepartment
     */
    public function setDepartmentId($departmentId)
    {
        $this->departmentId = $departmentId;
    
        return $this;
    }

    /**
     * Get departmentId
     *
     * @return integer 
     */
    public function getDepartmentId()
    {
        return $this->departmentId;
    }
}