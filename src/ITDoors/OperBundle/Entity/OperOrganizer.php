<?php

namespace ITDoors\OperBundle\Entity;

use Doctrine\ORM\Query;

/**
 * OperOrganizer
 */
class OperOrganizer
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $startDatetime;

    /**
     * @var \DateTime
     */
    private $endDatetime;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $user;

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
     * Set startDatetime
     *
     * @param \DateTime $startDatetime
     *
     * @return OperOrganizer
     */
    public function setStartDatetime($startDatetime)
    {
        $this->startDatetime = $startDatetime;

        return $this;
    }

    /**
     * Get startDatetime
     *
     * @return \DateTime 
     */
    public function getStartDatetime()
    {
        return $this->startDatetime;
    }

    /**
     * Set endDatetime
     *
     * @param \DateTime $endDatetime
     *
     * @return OperOrganizer
     */
    public function setEndDatetime($endDatetime)
    {
        $this->endDatetime = $endDatetime;

        return $this;
    }

    /**
     * Get endDatetime
     *
     * @return \DateTime 
     */
    public function getEndDatetime()
    {
        return $this->endDatetime;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return OperOrganizer
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
     * Set department
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $department
     *
     * @return OperOrganizer
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
