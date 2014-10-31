<?php

namespace SD\UserBundle\Entity;

/**
 * StuffDepartments
 */
class StuffDepartments
{

    /**
     * @var string
     */
    private $userkey;

    /**
     * @var \SD\UserBundle\Entity\Claimtype
     */
    private $claimtypes;

    /**
     * @var \Lists\DepartmentBundle\Entity\Departments
     */
    private $departments;

    /**
     * @var \SD\UserBundle\Entity\Stuff
     */
    private $stuff;


    /**
     * Set userkey
     *
     * @param string $userkey
     *
     * @return StuffDepartments
     */
    public function setUserkey($userkey)
    {
        $this->userkey = $userkey;

        return $this;
    }

    /**
     * Get userkey
     *
     * @return string 
     */
    public function getUserkey()
    {
        return $this->userkey;
    }

    /**
     * Set claimtypes
     *
     * @param \SD\UserBundle\Entity\Claimtype $claimtypes
     *
     * @return StuffDepartments
     */
    public function setClaimtypes(\SD\UserBundle\Entity\Claimtype $claimtypes = null)
    {
        $this->claimtypes = $claimtypes;

        return $this;
    }

    /**
     * Get claimtypes
     *
     * @return \SD\UserBundle\Entity\Claimtype
     */
    public function getClaimtypes()
    {
        return $this->claimtypes;
    }

    /**
     * Set departments
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $departments
     *
     * @return StuffDepartments
     */
    public function setDepartments(\Lists\DepartmentBundle\Entity\Departments $departments = null)
    {
        $this->departments = $departments;

        return $this;
    }

    /**
     * Get departments
     *
     * @return \Lists\DepartmentBundle\Entity\Departments 
     */
    public function getDepartments()
    {
        return $this->departments;
    }

    /**
     * Set stuff
     *
     * @param \SD\UserBundle\Entity\Stuff $stuff
     *
     * @return StuffDepartments
     */
    public function setStuff(\SD\UserBundle\Entity\Stuff $stuff = null)
    {
        $this->stuff = $stuff;

        return $this;
    }

    /**
     * Get stuff
     *
     * @return \SD\UserBundle\Entity\Stuff 
     */
    public function getStuff()
    {
        return $this->stuff;
    }
    /**
     * @var integer
     */
    private $id;
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
