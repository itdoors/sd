<?php

namespace ITDoors\OperBundle\Entity;

use Doctrine\ORM\Query;

/**
 * OperOrganizer
 */
class OperOrganizer
{

    /**
     * constructor
     */
    public function __construct()
    {
        $this->setIsVisited(false);
    }

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
    /**
     * @var boolean
     */
    private $isVisited;


    /**
     * Set isVisited
     *
     * @param boolean $isVisited
     * 
     * @return OperOrganizer
     */
    public function setIsVisited($isVisited)
    {
        $this->isVisited = $isVisited;

        return $this;
    }

    /**
     * Get isVisited
     *
     * @return boolean 
     */
    public function getIsVisited()
    {
        return $this->isVisited;
    }
    /**
     * @var \ITDoors\OperBundle\Entity\OperOrganizerType
     */
    private $type;


    /**
     * Set type
     *
     * @param \ITDoors\OperBundle\Entity\OperOrganizerType $type
     * 
     * @return OperOrganizer
     */
    public function setType(\ITDoors\OperBundle\Entity\OperOrganizerType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \ITDoors\OperBundle\Entity\OperOrganizerType 
     */
    public function getType()
    {
        return $this->type;
    }
}
