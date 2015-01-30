<?php

namespace ITDoors\CalculateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CalculatorItem
 */
class CalculatorItem
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $calculatorPrices;

    /**
     * @var \ITDoors\CalculateBundle\Entity\CalculatorItem
     */
    private $parent;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->calculatorPrices = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return CalculatorItem
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
     * @return CalculatorItem
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
     * Add calculatorPrices
     *
     * @param \ITDoors\CalculateBundle\Entity\CalculatorPrice $calculatorPrices
     * @return CalculatorItem
     */
    public function addCalculatorPrice(\ITDoors\CalculateBundle\Entity\CalculatorPrice $calculatorPrices)
    {
        $this->calculatorPrices[] = $calculatorPrices;
    
        return $this;
    }

    /**
     * Remove calculatorPrices
     *
     * @param \ITDoors\CalculateBundle\Entity\CalculatorPrice $calculatorPrices
     */
    public function removeCalculatorPrice(\ITDoors\CalculateBundle\Entity\CalculatorPrice $calculatorPrices)
    {
        $this->calculatorPrices->removeElement($calculatorPrices);
    }

    /**
     * Get calculatorPrices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCalculatorPrices()
    {
        return $this->calculatorPrices;
    }

    /**
     * Set parent
     *
     * @param \ITDoors\CalculateBundle\Entity\CalculatorItem $parent
     * @return CalculatorItem
     */
    public function setParent(\ITDoors\CalculateBundle\Entity\CalculatorItem $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \ITDoors\CalculateBundle\Entity\CalculatorItem 
     */
    public function getParent()
    {
        return $this->parent;
    }
}