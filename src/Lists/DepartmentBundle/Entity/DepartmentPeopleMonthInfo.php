<?php

namespace Lists\DepartmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DepartmentPeopleMonthInfo
 */
class DepartmentPeopleMonthInfo
{
    /**
     * @var integer
     */
    private $departmentPeopleId;

    /**
     * @var integer
     */
    private $year;

    /**
     * @var integer
     */
    private $month;

    /**
     * @var float
     */
    private $bonus;

    /**
     * @var float
     */
    private $fine;

    /**
     * @var string
     */
    private $salary;

    /**
     * @var string
     */
    private $typeString;

    /**
     * @var boolean
     */
    private $isCleanSalary;

    /**
     * @var integer
     */
    private $normaDays;

    /**
     * @var string
     */
    private $realSalary;

    /**
     * @var float
     */
    private $surcharge;

    /**
     * @var \Lists\DepartmentBundle\Entity\DepartmentPeople
     */
    private $departmentPeopleReplacement;

    /**
     * @var \Lists\DepartmentBundle\Entity\Lookup
     */
    private $employmentType;

    /**
     * @var \Lists\DepartmentBundle\Entity\Lookup
     */
    private $salaryType;

    /**
     * @var \Lists\DepartmentBundle\Entity\Lookup
     */
    private $type;

    /**
     * @var \Lists\DepartmentBundle\Entity\DepartmentPeoplePosition
     */
    private $position;

    /**
     * @var \Lists\DepartmentBundle\Entity\Lookup
     */
    private $surchargeType;

    /**
     * @var \Lists\DepartmentBundle\Entity\Lookup
     */
    private $fineType;

    /**
     * @var \Lists\DepartmentBundle\Entity\Lookup
     */
    private $bonusType;


    /**
     * Set departmentPeopleId
     *
     * @param integer $departmentPeopleId
     * @return DepartmentPeopleMonthInfo
     */
    public function setDepartmentPeopleId($departmentPeopleId)
    {
        $this->departmentPeopleId = $departmentPeopleId;
    
        return $this;
    }

    /**
     * Get departmentPeopleId
     *
     * @return integer 
     */
    public function getDepartmentPeopleId()
    {
        return $this->departmentPeopleId;
    }

    /**
     * Set year
     *
     * @param integer $year
     * @return DepartmentPeopleMonthInfo
     */
    public function setYear($year)
    {
        $this->year = $year;
    
        return $this;
    }

    /**
     * Get year
     *
     * @return integer 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set month
     *
     * @param integer $month
     * @return DepartmentPeopleMonthInfo
     */
    public function setMonth($month)
    {
        $this->month = $month;
    
        return $this;
    }

    /**
     * Get month
     *
     * @return integer 
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set bonus
     *
     * @param float $bonus
     * @return DepartmentPeopleMonthInfo
     */
    public function setBonus($bonus)
    {
        $this->bonus = $bonus;
    
        return $this;
    }

    /**
     * Get bonus
     *
     * @return float 
     */
    public function getBonus()
    {
        return $this->bonus;
    }

    /**
     * Set fine
     *
     * @param float $fine
     * @return DepartmentPeopleMonthInfo
     */
    public function setFine($fine)
    {
        $this->fine = $fine;
    
        return $this;
    }

    /**
     * Get fine
     *
     * @return float 
     */
    public function getFine()
    {
        return $this->fine;
    }

    /**
     * Set salary
     *
     * @param string $salary
     * @return DepartmentPeopleMonthInfo
     */
    public function setSalary($salary)
    {
        $this->salary = $salary;
    
        return $this;
    }

    /**
     * Get salary
     *
     * @return string 
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * Set typeString
     *
     * @param string $typeString
     * @return DepartmentPeopleMonthInfo
     */
    public function setTypeString($typeString)
    {
        $this->typeString = $typeString;
    
        return $this;
    }

    /**
     * Get typeString
     *
     * @return string 
     */
    public function getTypeString()
    {
        return $this->typeString;
    }

    /**
     * Set isCleanSalary
     *
     * @param boolean $isCleanSalary
     * @return DepartmentPeopleMonthInfo
     */
    public function setIsCleanSalary($isCleanSalary)
    {
        $this->isCleanSalary = $isCleanSalary;
    
        return $this;
    }

    /**
     * Get isCleanSalary
     *
     * @return boolean 
     */
    public function getIsCleanSalary()
    {
        return $this->isCleanSalary;
    }

    /**
     * Set normaDays
     *
     * @param integer $normaDays
     * @return DepartmentPeopleMonthInfo
     */
    public function setNormaDays($normaDays)
    {
        $this->normaDays = $normaDays;
    
        return $this;
    }

    /**
     * Get normaDays
     *
     * @return integer 
     */
    public function getNormaDays()
    {
        return $this->normaDays;
    }

    /**
     * Set realSalary
     *
     * @param string $realSalary
     * @return DepartmentPeopleMonthInfo
     */
    public function setRealSalary($realSalary)
    {
        $this->realSalary = $realSalary;
    
        return $this;
    }

    /**
     * Get realSalary
     *
     * @return string 
     */
    public function getRealSalary()
    {
        return $this->realSalary;
    }

    /**
     * Set surcharge
     *
     * @param float $surcharge
     * @return DepartmentPeopleMonthInfo
     */
    public function setSurcharge($surcharge)
    {
        $this->surcharge = $surcharge;
    
        return $this;
    }

    /**
     * Get surcharge
     *
     * @return float 
     */
    public function getSurcharge()
    {
        return $this->surcharge;
    }

    /**
     * Set departmentPeopleReplacement
     *
     * @param \Lists\DepartmentBundle\Entity\DepartmentPeople $departmentPeopleReplacement
     * @return DepartmentPeopleMonthInfo
     */
    public function setDepartmentPeopleReplacement(\Lists\DepartmentBundle\Entity\DepartmentPeople $departmentPeopleReplacement = null)
    {
        $this->departmentPeopleReplacement = $departmentPeopleReplacement;
    
        return $this;
    }

    /**
     * Get departmentPeopleReplacement
     *
     * @return \Lists\DepartmentBundle\Entity\DepartmentPeople 
     */
    public function getDepartmentPeopleReplacement()
    {
        return $this->departmentPeopleReplacement;
    }

    /**
     * Set employmentType
     *
     * @param \Lists\DepartmentBundle\Entity\Lookup $employmentType
     * @return DepartmentPeopleMonthInfo
     */
    public function setEmploymentType(\Lists\DepartmentBundle\Entity\Lookup $employmentType = null)
    {
        $this->employmentType = $employmentType;
    
        return $this;
    }

    /**
     * Get employmentType
     *
     * @return \Lists\DepartmentBundle\Entity\Lookup 
     */
    public function getEmploymentType()
    {
        return $this->employmentType;
    }

    /**
     * Set salaryType
     *
     * @param \Lists\DepartmentBundle\Entity\Lookup $salaryType
     * @return DepartmentPeopleMonthInfo
     */
    public function setSalaryType(\Lists\DepartmentBundle\Entity\Lookup $salaryType = null)
    {
        $this->salaryType = $salaryType;
    
        return $this;
    }

    /**
     * Get salaryType
     *
     * @return \Lists\DepartmentBundle\Entity\Lookup 
     */
    public function getSalaryType()
    {
        return $this->salaryType;
    }

    /**
     * Set type
     *
     * @param \Lists\DepartmentBundle\Entity\Lookup $type
     * @return DepartmentPeopleMonthInfo
     */
    public function setType(\Lists\DepartmentBundle\Entity\Lookup $type = null)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return \Lists\DepartmentBundle\Entity\Lookup 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set position
     *
     * @param \Lists\DepartmentBundle\Entity\DepartmentPeoplePosition $position
     * @return DepartmentPeopleMonthInfo
     */
    public function setPosition(\Lists\DepartmentBundle\Entity\DepartmentPeoplePosition $position = null)
    {
        $this->position = $position;
    
        return $this;
    }

    /**
     * Get position
     *
     * @return \Lists\DepartmentBundle\Entity\DepartmentPeoplePosition 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set surchargeType
     *
     * @param \Lists\DepartmentBundle\Entity\Lookup $surchargeType
     * @return DepartmentPeopleMonthInfo
     */
    public function setSurchargeType(\Lists\DepartmentBundle\Entity\Lookup $surchargeType = null)
    {
        $this->surchargeType = $surchargeType;
    
        return $this;
    }

    /**
     * Get surchargeType
     *
     * @return \Lists\DepartmentBundle\Entity\Lookup 
     */
    public function getSurchargeType()
    {
        return $this->surchargeType;
    }

    /**
     * Set fineType
     *
     * @param \Lists\DepartmentBundle\Entity\Lookup $fineType
     * @return DepartmentPeopleMonthInfo
     */
    public function setFineType(\Lists\DepartmentBundle\Entity\Lookup $fineType = null)
    {
        $this->fineType = $fineType;
    
        return $this;
    }

    /**
     * Get fineType
     *
     * @return \Lists\DepartmentBundle\Entity\Lookup 
     */
    public function getFineType()
    {
        return $this->fineType;
    }

    /**
     * Set bonusType
     *
     * @param \Lists\DepartmentBundle\Entity\Lookup $bonusType
     * @return DepartmentPeopleMonthInfo
     */
    public function setBonusType(\Lists\DepartmentBundle\Entity\Lookup $bonusType = null)
    {
        $this->bonusType = $bonusType;
    
        return $this;
    }

    /**
     * Get bonusType
     *
     * @return \Lists\DepartmentBundle\Entity\Lookup 
     */
    public function getBonusType()
    {
        return $this->bonusType;
    }
}