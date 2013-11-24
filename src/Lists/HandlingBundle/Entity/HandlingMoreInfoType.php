<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HandlingMoreInfoType
 */
class HandlingMoreInfoType
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
    private $dataType;

    /**
     * @var string
     */
    private $enumChoices;

    /**
     * @var \Lists\HandlingBundle\Entity\HandlingResult
     */
    private $handlingResult;


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
     * @return HandlingMoreInfoType
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
     * Set dataType
     *
     * @param string $dataType
     * @return HandlingMoreInfoType
     */
    public function setDataType($dataType)
    {
        $this->dataType = $dataType;
    
        return $this;
    }

    /**
     * Get dataType
     *
     * @return string 
     */
    public function getDataType()
    {
        return $this->dataType;
    }

    /**
     * Set enumChoices
     *
     * @param string $enumChoices
     * @return HandlingMoreInfoType
     */
    public function setEnumChoices($enumChoices)
    {
        $this->enumChoices = $enumChoices;
    
        return $this;
    }

    /**
     * Get enumChoices
     *
     * @return string 
     */
    public function getEnumChoices()
    {
        return $this->enumChoices;
    }

    /**
     * Set handlingResult
     *
     * @param \Lists\HandlingBundle\Entity\HandlingResult $handlingResult
     * @return HandlingMoreInfoType
     */
    public function setHandlingResult(\Lists\HandlingBundle\Entity\HandlingResult $handlingResult = null)
    {
        $this->handlingResult = $handlingResult;
    
        return $this;
    }

    /**
     * Get handlingResult
     *
     * @return \Lists\HandlingBundle\Entity\HandlingResult 
     */
    public function getHandlingResult()
    {
        return $this->handlingResult;
    }
}