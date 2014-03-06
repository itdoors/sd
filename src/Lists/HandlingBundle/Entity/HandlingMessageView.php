<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HandlingMessageView
 */
class HandlingMessageView
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $handlingId;

    /**
     * @var \DateTime
     */
    private $createdate;

    /**
     * @var string
     */
    private $typeName;

    /**
     * @var string
     */
    private $typeSlug;

    /**
     * @var integer
     */
    private $typeStayactiontime;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     */
    private $additionalType;

    /**
     * @var string
     */
    private $userFullName;


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
     * Set handlingId
     *
     * @param integer $handlingId
     * @return HandlingMessageView
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
     * Set createdate
     *
     * @param \DateTime $createdate
     * @return HandlingMessageView
     */
    public function setCreatedate($createdate)
    {
        $this->createdate = $createdate;
    
        return $this;
    }

    /**
     * Get createdate
     *
     * @return \DateTime 
     */
    public function getCreatedate()
    {
        return $this->createdate;
    }

    /**
     * Set typeName
     *
     * @param string $typeName
     * @return HandlingMessageView
     */
    public function setTypeName($typeName)
    {
        $this->typeName = $typeName;
    
        return $this;
    }

    /**
     * Get typeName
     *
     * @return string 
     */
    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * Set typeSlug
     *
     * @param string $typeSlug
     * @return HandlingMessageView
     */
    public function setTypeSlug($typeSlug)
    {
        $this->typeSlug = $typeSlug;
    
        return $this;
    }

    /**
     * Get typeSlug
     *
     * @return string 
     */
    public function getTypeSlug()
    {
        return $this->typeSlug;
    }

    /**
     * Set typeStayactiontime
     *
     * @param integer $typeStayactiontime
     * @return HandlingMessageView
     */
    public function setTypeStayactiontime($typeStayactiontime)
    {
        $this->typeStayactiontime = $typeStayactiontime;
    
        return $this;
    }

    /**
     * Get typeStayactiontime
     *
     * @return integer 
     */
    public function getTypeStayactiontime()
    {
        return $this->typeStayactiontime;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return HandlingMessageView
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    
        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set additionalType
     *
     * @param string $additionalType
     * @return HandlingMessageView
     */
    public function setAdditionalType($additionalType)
    {
        $this->additionalType = $additionalType;
    
        return $this;
    }

    /**
     * Get additionalType
     *
     * @return string 
     */
    public function getAdditionalType()
    {
        return $this->additionalType;
    }

    /**
     * Set userFullName
     *
     * @param string $userFullName
     * @return HandlingMessageView
     */
    public function setUserFullName($userFullName)
    {
        $this->userFullName = $userFullName;
    
        return $this;
    }

    /**
     * Get userFullName
     *
     * @return string 
     */
    public function getUserFullName()
    {
        return $this->userFullName;
    }
}
