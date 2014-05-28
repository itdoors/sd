<?php

namespace Lists\HandlingBundle\Entity;

/**
 * HandlingMoreInfo
 */
class HandlingMoreInfo
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $value;

    /**
     * @var \Lists\HandlingBundle\Entity\Handling
     */
    private $handling;

    /**
     * @var \Lists\HandlingBundle\Entity\HandlingMoreInfoType
     */
    private $handlingMoreInfoType;

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
     * @param  string           $value
     * @return HandlingMoreInfo
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
     * Set handling
     *
     * @param  \Lists\HandlingBundle\Entity\Handling $handling
     * @return HandlingMoreInfo
     */
    public function setHandling(\Lists\HandlingBundle\Entity\Handling $handling = null)
    {
        $this->handling = $handling;

        return $this;
    }

    /**
     * Get handling
     *
     * @return \Lists\HandlingBundle\Entity\Handling
     */
    public function getHandling()
    {
        return $this->handling;
    }

    /**
     * Set handlingMoreInfoType
     *
     * @param  \Lists\HandlingBundle\Entity\HandlingMoreInfoType $handlingMoreInfoType
     * @return HandlingMoreInfo
     */
    public function setHandlingMoreInfoType(\Lists\HandlingBundle\Entity\HandlingMoreInfoType $handlingMoreInfoType = null)
    {
        $this->handlingMoreInfoType = $handlingMoreInfoType;

        return $this;
    }

    /**
     * Get handlingMoreInfoType
     *
     * @return \Lists\HandlingBundle\Entity\HandlingMoreInfoType
     */
    public function getHandlingMoreInfoType()
    {
        return $this->handlingMoreInfoType;
    }
    /**
     * @var integer
     */
    private $handlingId;

    /**
     * @var integer
     */
    private $handlingMoreInfoTypeId;

    /**
     * Set handlingId
     *
     * @param  integer          $handlingId
     * @return HandlingMoreInfo
     */
    public function setHandlingId($handlingId)
    {
        $this->handlingId = $handlingId;

        return $this;
    }

    /**
     * Get handlingId
     *
     * @return integer
     */
    public function getHandlingId()
    {
        return $this->handlingId;
    }

    /**
     * Set handlingMoreInfoTypeId
     *
     * @param  integer          $handlingMoreInfoTypeId
     * @return HandlingMoreInfo
     */
    public function setHandlingMoreInfoTypeId($handlingMoreInfoTypeId)
    {
        $this->handlingMoreInfoTypeId = $handlingMoreInfoTypeId;

        return $this;
    }

    /**
     * Get handlingMoreInfoTypeId
     *
     * @return integer
     */
    public function getHandlingMoreInfoTypeId()
    {
        return $this->handlingMoreInfoTypeId;
    }
}
