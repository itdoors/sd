<?php

namespace Lists\DepartmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OnceOnlyAccrual
 */
class OnceOnlyAccrual
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $workType;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $code;

    /**
     * @var integer
     */
    private $value;

    /**
     * @var string
     */
    private $description;

    /**
     * @var boolean
     */
    private $isActive;

    /**
     * @var \Lists\MpkBundle\Entity\Mpk
     */
    private $mpk;


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
     * Set workType
     *
     * @param string $workType
     * @return OnceOnlyAccrual
     */
    public function setWorkType($workType)
    {
        $this->workType = $workType;
    
        return $this;
    }

    /**
     * Get workType
     *
     * @return string 
     */
    public function getWorkType()
    {
        return $this->workType;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return OnceOnlyAccrual
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
     * @return OnceOnlyAccrual
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
     * @param integer $value
     * @return OnceOnlyAccrual
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return integer 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return OnceOnlyAccrual
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return OnceOnlyAccrual
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

    /**
     * Set mpk
     *
     * @param \Lists\MpkBundle\Entity\Mpk $mpk
     * @return OnceOnlyAccrual
     */
    public function setMpk(\Lists\MpkBundle\Entity\Mpk $mpk = null)
    {
        $this->mpk = $mpk;
    
        return $this;
    }

    /**
     * Get mpk
     *
     * @return \Lists\MpkBundle\Entity\Mpk 
     */
    public function getMpk()
    {
        return $this->mpk;
    }
}