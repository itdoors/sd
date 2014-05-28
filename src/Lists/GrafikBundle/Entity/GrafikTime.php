<?php

namespace Lists\GrafikBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrafikTime
 */
class GrafikTime
{
    /**
     * @var integer
     */
    private $id;

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
     * @var \Lists\DepartmentBundle\Entity\DepartmentPeople
     */
    private $departmentPeopleReplacement;

    /**
     * @var \Lists\DepartmentBundle\Entity\Departments
     */
    private $department;

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
     * Set year
     *
     * @param integer $year
     * @return GrafikTime
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
     * @return GrafikTime
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
     * @return GrafikTime
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
     * @return GrafikTime
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
     * @return GrafikTime
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
     * @return GrafikTime
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
     * Set totalDay
     *
     * @param float $totalDay
     * @return GrafikTime
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
     * @param float $totalEvening
     * @return GrafikTime
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
     * @param float $totalNight
     * @return GrafikTime
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
     * Set departmentPeopleReplacement
     *
     * @param \Lists\DepartmentBundle\Entity\DepartmentPeople $departmentPeopleReplacement
     * @return GrafikTime
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
     * Set department
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $department
     * @return GrafikTime
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
     * @return GrafikTime
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
     * @param float $totalNotOfficially
     * @return GrafikTime
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
     * @param float $totalDayNotOfficially
     * @return GrafikTime
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
     * @param float $totalEveningNotOfficially
     * @return GrafikTime
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
     * @param float $totalNightNotOfficially
     * @return GrafikTime
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
     * @var boolean
     */
    private $notOfficially;


    /**
     * Set notOfficially
     *
     * @param boolean $notOfficially
     * @return GrafikTime
     */
    public function setNotOfficially($notOfficially)
    {
        $this->notOfficially = $notOfficially;
    
        return $this;
    }

    /**
     * Get notOfficially
     *
     * @return boolean 
     */
    public function getNotOfficially()
    {
        return $this->notOfficially;
    }
}