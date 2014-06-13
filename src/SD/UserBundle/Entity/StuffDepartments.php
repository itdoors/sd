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
     * @var integer
     */
    private $id;

    /**
     * @var \SD\UserBundle\Entity\Claimtype
     */
    private $claimtype;

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
     * Set id
     *
     * @param integer $id
     *
     * @return StuffDepartments
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

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
     * Set claimtype
     *
     * @param \SD\UserBundle\Entity\Claimtype $claimtype
     *
     * @return StuffDepartments
     */
    public function setClaimtype(\SD\UserBundle\Entity\Claimtype $claimtype = null)
    {
        $this->claimtype = $claimtype;

        return $this;
    }

    /**
     * Get claimtype
     *
     * @return \SD\UserBundle\Entity\Claimtype 
     */
    public function getClaimtype()
    {
        return $this->claimtype;
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
}
