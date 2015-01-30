<?php

namespace ITDoors\CalculateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CalculatorPrice
 */
class CalculatorPrice
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $value;

    /**
     * @var string
     */
    private $unit;

    /**
     * @var \ITDoors\CalculateBundle\Entity\CalculatorItem
     */
    private $calculatorItem;


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
     * Set value
     *
     * @param float $value
     * @return CalculatorPrice
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return float 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set unit
     *
     * @param string $unit
     * @return CalculatorPrice
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    
        return $this;
    }

    /**
     * Get unit
     *
     * @return string 
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set calculatorItem
     *
     * @param \ITDoors\CalculateBundle\Entity\CalculatorItem $calculatorItem
     * @return CalculatorPrice
     */
    public function setCalculatorItem(\ITDoors\CalculateBundle\Entity\CalculatorItem $calculatorItem = null)
    {
        $this->calculatorItem = $calculatorItem;
    
        return $this;
    }

    /**
     * Get calculatorItem
     *
     * @return \ITDoors\CalculateBundle\Entity\CalculatorItem 
     */
    public function getCalculatorItem()
    {
        return $this->calculatorItem;
    }
}