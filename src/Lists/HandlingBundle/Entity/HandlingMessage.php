<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HandlingMessage
 */
class HandlingMessage
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdatetime;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $createdate;

    /**
     * @var string
     */
    private $filepath;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var \Lists\HandlingBundle\Entity\Handling
     */
    private $handling;

    /**
     * @var \Lists\HandlingBundle\Entity\HandlingMessageType
     */
    private $type;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $user;


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
     * Set createdatetime
     *
     * @param \DateTime $createdatetime
     * @return HandlingMessage
     */
    public function setCreatedatetime($createdatetime)
    {
        $this->createdatetime = $createdatetime;
    
        return $this;
    }

    /**
     * Get createdatetime
     *
     * @return \DateTime 
     */
    public function getCreatedatetime()
    {
        return $this->createdatetime;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return HandlingMessage
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
     * Set createdate
     *
     * @param \DateTime $createdate
     * @return HandlingMessage
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
     * Set filepath
     *
     * @param string $filepath
     * @return HandlingMessage
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;
    
        return $this;
    }

    /**
     * Get filepath
     *
     * @return string 
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * Set filename
     *
     * @param string $filename
     * @return HandlingMessage
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    
        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set handling
     *
     * @param \Lists\HandlingBundle\Entity\Handling $handling
     * @return HandlingMessage
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
     * Set type
     *
     * @param \Lists\HandlingBundle\Entity\HandlingMessageType $type
     * @return HandlingMessage
     */
    public function setType(\Lists\HandlingBundle\Entity\HandlingMessageType $type = null)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return \Lists\HandlingBundle\Entity\HandlingMessageType 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * @return HandlingMessage
     */
    public function setUser(\SD\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}