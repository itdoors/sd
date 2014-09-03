<?php

namespace ITDoors\SipBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Call
 */
class Call
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $callerId;

    /**
     * @var integer
     */
    private $peerId;

    /**
     * @var integer
     */
    private $receiverId;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $proxyId;

    /**
     * @var string
     */
    private $uniqueId;

    /**
     * @var string
     */
    private $destuniqueId;

    /**
     * @var \DateTime
     */
    private $datetime;

    /**
     * @var integer
     */
    private $duration;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $modelName;

    /**
     * @var integer
     */
    private $modelId;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $caller;


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
     * Set callerId
     *
     * @param integer $callerId
     * @return Call
     */
    public function setCallerId($callerId)
    {
        $this->callerId = $callerId;
    
        return $this;
    }

    /**
     * Get callerId
     *
     * @return integer 
     */
    public function getCallerId()
    {
        return $this->callerId;
    }

    /**
     * Set peerId
     *
     * @param integer $peerId
     * @return Call
     */
    public function setPeerId($peerId)
    {
        $this->peerId = $peerId;
    
        return $this;
    }

    /**
     * Get peerId
     *
     * @return integer 
     */
    public function getPeerId()
    {
        return $this->peerId;
    }

    /**
     * Set receiverId
     *
     * @param integer $receiverId
     * @return Call
     */
    public function setReceiverId($receiverId)
    {
        $this->receiverId = $receiverId;
    
        return $this;
    }

    /**
     * Get receiverId
     *
     * @return integer 
     */
    public function getReceiverId()
    {
        return $this->receiverId;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Call
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set proxyId
     *
     * @param string $proxyId
     * @return Call
     */
    public function setProxyId($proxyId)
    {
        $this->proxyId = $proxyId;
    
        return $this;
    }

    /**
     * Get proxyId
     *
     * @return string 
     */
    public function getProxyId()
    {
        return $this->proxyId;
    }

    /**
     * Set uniqueId
     *
     * @param string $uniqueId
     * @return Call
     */
    public function setUniqueId($uniqueId)
    {
        $this->uniqueId = $uniqueId;
    
        return $this;
    }

    /**
     * Get uniqueId
     *
     * @return string 
     */
    public function getUniqueId()
    {
        return $this->uniqueId;
    }

    /**
     * Set destuniqueId
     *
     * @param string $destuniqueId
     * @return Call
     */
    public function setDestuniqueId($destuniqueId)
    {
        $this->destuniqueId = $destuniqueId;
    
        return $this;
    }

    /**
     * Get destuniqueId
     *
     * @return string 
     */
    public function getDestuniqueId()
    {
        return $this->destuniqueId;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return Call
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
    
        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime 
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return Call
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    
        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return Call
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    
        return $this;
    }

    /**
     * Get fileName
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Call
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set modelName
     *
     * @param string $modelName
     * @return Call
     */
    public function setModelName($modelName)
    {
        $this->modelName = $modelName;
    
        return $this;
    }

    /**
     * Get modelName
     *
     * @return string 
     */
    public function getModelName()
    {
        return $this->modelName;
    }

    /**
     * Set modelId
     *
     * @param integer $modelId
     * @return Call
     */
    public function setModelId($modelId)
    {
        $this->modelId = $modelId;
    
        return $this;
    }

    /**
     * Get modelId
     *
     * @return integer 
     */
    public function getModelId()
    {
        return $this->modelId;
    }

    /**
     * Set caller
     *
     * @param \SD\UserBundle\Entity\User $caller
     * @return Call
     */
    public function setCaller(\SD\UserBundle\Entity\User $caller = null)
    {
        $this->caller = $caller;
    
        return $this;
    }

    /**
     * Get caller
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getCaller()
    {
        return $this->caller;
    }
}
