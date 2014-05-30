<?php

namespace Lists\GrafikBundle\Entity;

/**
 * Grafik
 */
class Grafik
{
    /**
     * @var integer
     */
    private $year;

    /**
     * @var integer
     */
    private $month;

    /**
     * @var integer
     */
    private $day;

    /**
     * @var \DateTime
     */
    private $fromTime;

    /**
     * @var \DateTime
     */
    private $toTime;

    /**
     * @var float
     */
    private $total;

    /**
     * @var boolean
     */
    private $isSick;

    /**
     * @var boolean
     */
    private $isSkip;

    /**
     * @var boolean
     */
    private $isFired;

    /**
     * @var boolean
     */
    private $isVacation;

    /**
     * @var float
     */
    private $totalDay;

    /**
     * @var float
     */
    private $totalEvening;

    /**
     * @var float
     */
    private $totalNight;

    /**
     * @var \Lists\DepartmentBundle\Entity\Departments
     */
    private $department;

    /**
     * @var \Lists\DepartmentBundle\Entity\DepartmentPeople
     */
    private $departmentPeople;

    /**
     * @var \Lists\DepartmentBundle\Entity\DepartmentPeople
     */
    private $departmentPeopleReplacement;

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return Grafik
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
     * @return Grafik
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
     * Set day
     *
     * @param integer $day
     *
     * @return Grafik
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return integer
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set fromTime
     *
     * @param \DateTime $fromTime
     *
     * @return Grafik
     */
    public function setFromTime($fromTime)
    {
        $this->fromTime = $fromTime;

        return $this;
    }

    /**
     * Get fromTime
     *
     * @return \DateTime
     */
    public function getFromTime()
    {
        return $this->fromTime;
    }

    /**
     * Set toTime
     *
     * @param \DateTime $toTime
     *
     * @return Grafik
     */
    public function setToTime($toTime)
    {
        $this->toTime = $toTime;

        return $this;
    }

    /**
     * Get toTime
     *
     * @return \DateTime
     */
    public function getToTime()
    {
        return $this->toTime;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return Grafik
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set isSick
     *
     * @param boolean $isSick
     *
     * @return Grafik
     */
    public function setIsSick($isSick)
    {
        $this->isSick = $isSick;

        return $this;
    }

    /**
     * Get isSick
     *
     * @return boolean
     */
    public function getIsSick()
    {
        return $this->isSick;
    }

    /**
     * Set isSkip
     *
     * @param boolean $isSkip
     *
     * @return Grafik
     */
    public function setIsSkip($isSkip)
    {
        $this->isSkip = $isSkip;

        return $this;
    }

    /**
     * Get isSkip
     *
     * @return boolean
     */
    public function getIsSkip()
    {
        return $this->isSkip;
    }

    /**
     * Set isFired
     *
     * @param boolean $isFired
     *
     * @return Grafik
     */
    public function setIsFired($isFired)
    {
        $this->isFired = $isFired;

        return $this;
    }

    /**
     * Get isFired
     *
     * @return boolean
     */
    public function getIsFired()
    {
        return $this->isFired;
    }

    /**
     * Set isVacation
     *
     * @param boolean $isVacation
     *
     * @return Grafik
     */
    public function setIsVacation($isVacation)
    {
        $this->isVacation = $isVacation;

        return $this;
    }

    /**
     * Get isVacation
     *
     * @return boolean
     */
    public function getIsVacation()
    {
        return $this->isVacation;
    }

    /**
     * Set totalDay
     *
     * @param float  $totalDay
     *
     * @return Grafik
     */
    public function setTotalDay($totalDay)
    {
        $this->totalDay = $totalDay;

        return $this;
    }

    /**
     * Get totalDay
     *
     * @return float
     */
    public function getTotalDay()
    {
        return $this->totalDay;
    }

    /**
     * Set totalEvening
     *
     * @param float  $totalEvening
     *
     * @return Grafik
     */
    public function setTotalEvening($totalEvening)
    {
        $this->totalEvening = $totalEvening;

        return $this;
    }

    /**
     * Get totalEvening
     *
     * @return float
     */
    public function getTotalEvening()
    {
        return $this->totalEvening;
    }

    /**
     * Set totalNight
     *
     * @param float  $totalNight
     *
     * @return Grafik
     */
    public function setTotalNight($totalNight)
    {
        $this->totalNight = $totalNight;

        return $this;
    }

    /**
     * Get totalNight
     *
     * @return float
     */
    public function getTotalNight()
    {
        return $this->totalNight;
    }

    /**
     * Set department
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $department
     *
     * @return Grafik
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
     * Set departmentPeople
     *
     * @param \Lists\DepartmentBundle\Entity\DepartmentPeople $departmentPeople
     *
     * @return Grafik
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
     * Set departmentPeopleReplacement
     *
     * @param \Lists\DepartmentBundle\Entity\DepartmentPeople $departmentPeopleReplacement
     *
     * @return Grafik
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
     * @var float
     */
    private $totalNotOfficially;

    /**
     * @var float
     */
    private $totalDayNotOfficially;

    /**
     * @var float
     */
    private $totalEveningNotOfficially;

    /**
     * @var float
     */
    private $totalNightNotOfficially;

    /**
     * Set totalNotOfficially
     *
     * @param float  $totalNotOfficially
     *
     * @return Grafik
     */
    public function setTotalNotOfficially($totalNotOfficially)
    {
        $this->totalNotOfficially = $totalNotOfficially;

        return $this;
    }

    /**
     * Get totalNotOfficially
     *
     * @return float
     */
    public function getTotalNotOfficially()
    {
        return $this->totalNotOfficially;
    }

    /**
     * Set totalDayNotOfficially
     *
     * @param float  $totalDayNotOfficially
     *
     * @return Grafik
     */
    public function setTotalDayNotOfficially($totalDayNotOfficially)
    {
        $this->totalDayNotOfficially = $totalDayNotOfficially;

        return $this;
    }

    /**
     * Get totalDayNotOfficially
     *
     * @return float
     */
    public function getTotalDayNotOfficially()
    {
        return $this->totalDayNotOfficially;
    }

    /**
     * Set totalEveningNotOfficially
     *
     * @param float  $totalEveningNotOfficially
     *
     * @return Grafik
     */
    public function setTotalEveningNotOfficially($totalEveningNotOfficially)
    {
        $this->totalEveningNotOfficially = $totalEveningNotOfficially;

        return $this;
    }

    /**
     * Get totalEveningNotOfficially
     *
     * @return float
     */
    public function getTotalEveningNotOfficially()
    {
        return $this->totalEveningNotOfficially;
    }

    /**
     * Set totalNightNotOfficially
     *
     * @param float  $totalNightNotOfficially
     *
     * @return Grafik
     */
    public function setTotalNightNotOfficially($totalNightNotOfficially)
    {
        $this->totalNightNotOfficially = $totalNightNotOfficially;

        return $this;
    }

    /**
     * Get totalNightNotOfficially
     *
     * @return float
     */
    public function getTotalNightNotOfficially()
    {
        return $this->totalNightNotOfficially;
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
     * @return Grafik
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
    private $departmentId;

    /**
     * @var integer
     */
    private $departmentPeopleId;

    /**
     * @var integer
     */
    private $departmentPeopleReplacementId;


    /**
     * Set departmentId
     *
     * @param integer $departmentId
     * @return Grafik
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

    /**
     * Set departmentPeopleId
     *
     * @param integer $departmentPeopleId
     * @return Grafik
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
     * Set departmentPeopleReplacementId
     *
     * @param integer $departmentPeopleReplacementId
     * @return Grafik
     */
    public function setDepartmentPeopleReplacementId($departmentPeopleReplacementId)
    {
        $this->departmentPeopleReplacementId = $departmentPeopleReplacementId;
    
        return $this;
    }

    /**
     * Get departmentPeopleReplacementId
     *
     * @return integer 
     */
    public function getDepartmentPeopleReplacementId()
    {
        return $this->departmentPeopleReplacementId;
    }
}