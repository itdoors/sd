<?php

namespace Lists\GrafikBundle\Entity;

/**
 * Class Salary
 */
class Salary
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
    private $daysCount;

    /**
     * @var string
     */
    private $weekends;

    /**
     * @var float
     */
    private $daySalary;

    /**
     * @var float
     */
    private $summaryCoef;


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
     *
     * @return Salary
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
     * @return Salary
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
     * Set daysCount
     *
     * @param integer $daysCount
     *
     * @return Salary
     */
    public function setDaysCount($daysCount)
    {
        $this->daysCount = $daysCount;

        return $this;
    }

    /**
     * Get daysCount
     *
     * @return integer 
     */
    public function getDaysCount()
    {
        return $this->daysCount;
    }

    /**
     * Set weekends
     *
     * @param string $weekends
     *
     * @return Salary
     */
    public function setWeekends($weekends)
    {
        $this->weekends = $weekends;

        return $this;
    }

    /**
     * Get weekends
     *
     * @return string 
     */
    public function getWeekends()
    {
        return $this->weekends;
    }

    /**
     * Set daySalary
     *
     * @param float $daySalary
     *
     * @return Salary
     */
    public function setDaySalary($daySalary)
    {
        $this->daySalary = $daySalary;

        return $this;
    }

    /**
     * Get daySalary
     *
     * @return float 
     */
    public function getDaySalary()
    {
        return $this->daySalary;
    }

    /**
     * Set summaryCoef
     *
     * @param float $summaryCoef
     *
     * @return Salary
     */
    public function setSummaryCoef($summaryCoef)
    {
        $this->summaryCoef = $summaryCoef;

        return $this;
    }

    /**
     * Get summaryCoef
     *
     * @return float 
     */
    public function getSummaryCoef()
    {
        return $this->summaryCoef;
    }
    /**
     * @var integer
     */
    private $dayAdvance;

    /**
     * @var integer
     */
    private $dayPayment;


    /**
     * Set dayAdvance
     *
     * @param integer $dayAdvance
     *
     * @return Salary
     */
    public function setDayAdvance($dayAdvance)
    {
        $this->dayAdvance = $dayAdvance;

        return $this;
    }

    /**
     * Get dayAdvance
     *
     * @return integer 
     */
    public function getDayAdvance()
    {
        return $this->dayAdvance;
    }

    /**
     * Set dayPayment
     *
     * @param integer $dayPayment
     *
     * @return Salary
     */
    public function setDayPayment($dayPayment)
    {
        $this->dayPayment = $dayPayment;

        return $this;
    }

    /**
     * Get dayPayment
     *
     * @return integer 
     */
    public function getDayPayment()
    {
        return $this->dayPayment;
    }
}