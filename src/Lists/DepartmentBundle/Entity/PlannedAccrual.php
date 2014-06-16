<?php

namespace Lists\DepartmentBundle\Entity;

/**
 * PlannedAccrual
 */
class PlannedAccrual
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
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $value;

    /**
     * @var \DateTime
     */
    private $period;

    /**
     * @var \Lists\DepartmentBundle\Entity\DepartmentPeople
     */
    private $departmentPeople;


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
     *
     * @return PlannedAccrual
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
     * Set type
     *
     * @param string $type
     *
     * @return PlannedAccrual
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return PlannedAccrual
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return PlannedAccrual
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set period
     *
     * @param \DateTime $period
     *
     * @return PlannedAccrual
     */
    public function setPeriod($period)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Get period
     *
     * @return \DateTime 
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Set departmentPeople
     *
     * @param \Lists\DepartmentBundle\Entity\DepartmentPeople $departmentPeople
     *
     * @return PlannedAccrual
     */
    public function setDepartmentPeople(\Lists\DepartmentBundle\Entity\DepartmentPeople $departmentPeople = null)
    {
        $this->departmentPeople = $departmentPeople;

        return $this;
    }

    /**
     * Get departmentPeople
     *
     * @return \Lists\DepartmentBundle\Entity\DepartmentPeople 
     */
    public function getDepartmentPeople()
    {
        return $this->departmentPeople;
    }
    /**
     * @var boolean
     */
    private $isActive;


    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return PlannedAccrual
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
}