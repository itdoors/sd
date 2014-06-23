<?php

namespace Lists\DepartmentBundle\Entity;

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
     * @var \Lists\LookupBundle\Entity\Lookup
     */
    private $employmentType;

    /**
     * @var \Lists\LookupBundle\Entity\Lookup
     */
    private $salaryType;

    /**
     * @var \Lists\LookupBundle\Entity\Lookup
     */
    private $type;

    /**
     * @var \Lists\DepartmentBundle\Entity\DepartmentPeoplePosition
     */
    private $position;

    /**
     * @var \Lists\LookupBundle\Entity\Lookup
     */
    private $surchargeType;

    /**
     * @var \Lists\LookupBundle\Entity\Lookup
     */
    private $fineType;

    /**
     * @var \Lists\LookupBundle\Entity\Lookup
     */
    private $bonusType;

    /**
     * Set departmentPeopleId
     *
     * @param integer $departmentPeopleId
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
     * @return DepartmentPeopleMonthInfo
     */
    public function setDepartmentPeopleReplacement(
        \Lists\DepartmentBundle\Entity\DepartmentPeople $departmentPeopleReplacement = null
    // @codingStandardsIgnoreStart
    )
    {
    // @codingStandardsIgnoreEnd
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
     * @param \Lists\LookupBundle\Entity\Lookup $employmentType
     *
     * @return DepartmentPeopleMonthInfo
     */
    public function setEmploymentType(\Lists\LookupBundle\Entity\Lookup $employmentType = null)
    {
        $this->employmentType = $employmentType;

        return $this;
    }

    /**
     * Get employmentType
     *
     * @return \Lists\LookupBundle\Entity\Lookup
     */
    public function getEmploymentType()
    {
        return $this->employmentType;
    }

    /**
     * Set salaryType
     *
     * @param \Lists\LookupBundle\Entity\Lookup $salaryType
     *
     * @return DepartmentPeopleMonthInfo
     */
    public function setSalaryType(\Lists\LookupBundle\Entity\Lookup $salaryType = null)
    {
        $this->salaryType = $salaryType;

        return $this;
    }

    /**
     * Get salaryType
     *
     * @return \Lists\LookupBundle\Entity\Lookup
     */
    public function getSalaryType()
    {
        return $this->salaryType;
    }

    /**
     * Set type
     *
     * @param \Lists\LookupBundle\Entity\Lookup $type
     *
     * @return DepartmentPeopleMonthInfo
     */
    public function setType(\Lists\LookupBundle\Entity\Lookup $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Lists\LookupBundle\Entity\Lookup
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set position
     *
     * @param \Lists\DepartmentBundle\Entity\DepartmentPeoplePosition $position
     *
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
     * @param \Lists\LookupBundle\Entity\Lookup $surchargeType
     *
     * @return DepartmentPeopleMonthInfo
     */
    public function setSurchargeType(\Lists\LookupBundle\Entity\Lookup $surchargeType = null)
    {
        $this->surchargeType = $surchargeType;

        return $this;
    }

    /**
     * Get surchargeType
     *
     * @return \Lists\LookupBundle\Entity\Lookup
     */
    public function getSurchargeType()
    {
        return $this->surchargeType;
    }

    /**
     * Set fineType
     *
     * @param \Lists\LookupBundle\Entity\Lookup $fineType
     *
     * @return DepartmentPeopleMonthInfo
     */
    public function setFineType(\Lists\LookupBundle\Entity\Lookup $fineType = null)
    {
        $this->fineType = $fineType;

        return $this;
    }

    /**
     * Get fineType
     *
     * @return \Lists\LookupBundle\Entity\Lookup
     */
    public function getFineType()
    {
        return $this->fineType;
    }

    /**
     * Set bonusType
     *
     * @param \Lists\LookupBundle\Entity\Lookup $bonusType
     *
     * @return DepartmentPeopleMonthInfo
     */
    public function setBonusType(\Lists\LookupBundle\Entity\Lookup $bonusType = null)
    {
        $this->bonusType = $bonusType;

        return $this;
    }

    /**
     * Get bonusType
     *
     * @return \Lists\LookupBundle\Entity\Lookup
     */
    public function getBonusType()
    {
        return $this->bonusType;
    }
    /**
     * @var \Lists\DepartmentBundle\Entity\DepartmentPeople
     */
    private $departmentPeople;


    /**
     * Set departmentPeople
     *
     * @param \Lists\DepartmentBundle\Entity\DepartmentPeople $departmentPeople
     *
     * @return DepartmentPeopleMonthInfo
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
     * @var string
     */
    private $replacementType;


    /**
     * Set replacementType
     *
     * @param string $replacementType
     *
     * @return DepartmentPeopleMonthInfo
     */
    public function setReplacementType($replacementType)
    {
        $this->replacementType = $replacementType;

        return $this;
    }

    /**
     * Get replacementType
     *
     * @return string 
     */
    public function getReplacementType()
    {
        return $this->replacementType;
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
    /**
     * @var string
     */
    private $fineDescription;

    /**
     * @var string
     */
    private $surchargeDescription;

    /**
     * @var string
     */
    private $bonusDescription;


    /**
     * Set fineDescription
     *
     * @param string $fineDescription
     *
     * @return DepartmentPeopleMonthInfo
     */
    public function setFineDescription($fineDescription)
    {
        $this->fineDescription = $fineDescription;

        return $this;
    }

    /**
     * Get fineDescription
     *
     * @return string 
     */
    public function getFineDescription()
    {
        return $this->fineDescription;
    }

    /**
     * Set surchargeDescription
     *
     * @param string $surchargeDescription
     *
     * @return DepartmentPeopleMonthInfo
     */
    public function setSurchargeDescription($surchargeDescription)
    {
        $this->surchargeDescription = $surchargeDescription;

        return $this;
    }

    /**
     * Get surchargeDescription
     *
     * @return string 
     */
    public function getSurchargeDescription()
    {
        return $this->surchargeDescription;
    }

    /**
     * Set bonusDescription
     *
     * @param string $bonusDescription
     *
     * @return DepartmentPeopleMonthInfo
     */
    public function setBonusDescription($bonusDescription)
    {
        $this->bonusDescription = $bonusDescription;

        return $this;
    }

    /**
     * Get bonusDescription
     *
     * @return string 
     */
    public function getBonusDescription()
    {
        return $this->bonusDescription;
    }
    /**
     * @var string
     */
    private $fineTypeKey;

    /**
     * @var string
     */
    private $surchargeTypeKey;

    /**
     * @var string
     */
    private $bonusTypeKey;


    /**
     * Set fineTypeKey
     *
     * @param string $fineTypeKey
     *
     * @return DepartmentPeopleMonthInfo
     */
    public function setFineTypeKey($fineTypeKey)
    {
        $this->fineTypeKey = $fineTypeKey;

        return $this;
    }

    /**
     * Get fineTypeKey
     *
     * @return string 
     */
    public function getFineTypeKey()
    {
        return $this->fineTypeKey;
    }

    /**
     * Set surchargeTypeKey
     *
     * @param string $surchargeTypeKey
     *
     * @return DepartmentPeopleMonthInfo
     */
    public function setSurchargeTypeKey($surchargeTypeKey)
    {
        $this->surchargeTypeKey = $surchargeTypeKey;

        return $this;
    }

    /**
     * Get surchargeTypeKey
     *
     * @return string 
     */
    public function getSurchargeTypeKey()
    {
        return $this->surchargeTypeKey;
    }

    /**
     * Set bonusTypeKey
     *
     * @param string $bonusTypeKey
     *
     * @return DepartmentPeopleMonthInfo
     */
    public function setBonusTypeKey($bonusTypeKey)
    {
        $this->bonusTypeKey = $bonusTypeKey;

        return $this;
    }

    /**
     * Get bonusTypeKey
     *
     * @return string 
     */
    public function getBonusTypeKey()
    {
        return $this->bonusTypeKey;
    }

    /**
     * @var integer
     */
    private $replacementId;

    /**
     * Set replacementId
     *
     * @param integer $replacementId
     *
     * @return DepartmentPeopleMonthInfo
     */
    public function setReplacementId($replacementId)
    {
        $this->replacementId = $replacementId;

        return $this;
    }

    /**
     * Get replacementId
     *
     * @return integer 
     */
    public function getReplacementId()
    {
        return $this->replacementId;
    }
    /**
     * @var string
     */
    private $salaryOfficially;

    /**
     * @var string
     */
    private $salaryNotOfficially;


    /**
     * Set salaryOfficially
     *
     * @param string $salaryOfficially
     *
     * @return DepartmentPeopleMonthInfo
     */
    public function setSalaryOfficially($salaryOfficially)
    {
        $this->salaryOfficially = $salaryOfficially;

        return $this;
    }

    /**
     * Get salaryOfficially
     *
     * @return string 
     */
    public function getSalaryOfficially()
    {
        return $this->salaryOfficially;
    }

    /**
     * Set salaryNotOfficially
     *
     * @param string $salaryNotOfficially
     *
     * @return DepartmentPeopleMonthInfo
     */
    public function setSalaryNotOfficially($salaryNotOfficially)
    {
        $this->salaryNotOfficially = $salaryNotOfficially;

        return $this;
    }

    /**
     * Get salaryNotOfficially
     *
     * @return string 
     */
    public function getSalaryNotOfficially()
    {
        return $this->salaryNotOfficially;
    }
    /**
     * @var boolean
     */
    private $isGph;


    /**
     * Set isGph
     *
     * @param boolean $isGph
     * @return DepartmentPeopleMonthInfo
     */
    public function setIsGph($isGph)
    {
        $this->isGph = $isGph;
    
        return $this;
    }

    /**
     * Get isGph
     *
     * @return boolean 
     */
    public function getIsGph()
    {
        return $this->isGph;
    }
}