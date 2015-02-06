<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * File
 */
class File
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
    private $shortText;

    /**
     * @var \DateTime
     */
    private $createDatetime;

    /**
     * @var \DateTime
     */
    private $deletedDatetime;

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
     * Set name
     *
     * @param string $name
     * @return File
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
     * Set shortText
     *
     * @param string $shortText
     * @return File
     */
    public function setShortText($shortText)
    {
        $this->shortText = $shortText;
    
        return $this;
    }

    /**
     * Get shortText
     *
     * @return string 
     */
    public function getShortText()
    {
        return $this->shortText;
    }

    /**
     * Set createDatetime
     *
     * @param \DateTime $createDatetime
     * @return File
     */
    public function setCreateDatetime($createDatetime)
    {
        $this->createDatetime = $createDatetime;
    
        return $this;
    }

    /**
     * Get createDatetime
     *
     * @return \DateTime 
     */
    public function getCreateDatetime()
    {
        return $this->createDatetime;
    }

    /**
     * Set deletedDatetime
     *
     * @param \DateTime $deletedDatetime
     * @return File
     */
    public function setDeletedDatetime($deletedDatetime)
    {
        $this->deletedDatetime = $deletedDatetime;
    
        return $this;
    }

    /**
     * Get deletedDatetime
     *
     * @return \DateTime 
     */
    public function getDeletedDatetime()
    {
        return $this->deletedDatetime;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * @return File
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