<?php

namespace SD\CalendarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 */
class Task
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var \DateTime
     */
    private $createDateTime;

    /**
     * @var string
     */
    private $taskType;

    /**
     * @var \DateTime
     */
    private $startDateTime;

    /**
     * @var \DateTime
     */
    private $stopDateTime;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

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
     * Set userId
     *
     * @param integer $userId
     * 
     * @return Task
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
     * Set createDateTime
     *
     * @param \DateTime $createDateTime
     * 
     * @return Task
     */
    public function setCreateDateTime($createDateTime)
    {
        $this->createDateTime = $createDateTime;

        return $this;
    }

    /**
     * Get createDateTime
     *
     * @return \DateTime 
     */
    public function getCreateDateTime()
    {
        return $this->createDateTime;
    }

    /**
     * Set taskType
     *
     * @param string $taskType
     * 
     * @return Task
     */
    public function setTaskType($taskType)
    {
        $this->taskType = $taskType;

        return $this;
    }

    /**
     * Get taskType
     *
     * @return string 
     */
    public function getTaskType()
    {
        return $this->taskType;
    }

    /**
     * Set startDateTime
     *
     * @param \DateTime $startDateTime
     * 
     * @return Task
     */
    public function setStartDateTime($startDateTime)
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    /**
     * Get startDateTime
     *
     * @return \DateTime 
     */
    public function getStartDateTime()
    {
        return $this->startDateTime;
    }

    /**
     * Set stopDateTime
     *
     * @param \DateTime $stopDateTime
     * 
     * @return Task
     */
    public function setStopDateTime($stopDateTime)
    {
        $this->stopDateTime = $stopDateTime;

        return $this;
    }

    /**
     * Get stopDateTime
     *
     * @return \DateTime 
     */
    public function getStopDateTime()
    {
        return $this->stopDateTime;
    }

    /**
     * Set title
     *
     * @param string $title
     * 
     * @return Task
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * 
     * @return Task
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
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * 
     * @return Task
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
    /**
     * @var boolean
     */
    private $isDone;


    /**
     * Set isDone
     *
     * @param boolean $isDone
     *
     * @return Task
     */
    public function setIsDone($isDone)
    {
        $this->isDone = $isDone;

        return $this;
    }

    /**
     * Get isDone
     *
     * @return boolean 
     */
    public function getIsDone()
    {
        return $this->isDone;
    }
    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $performer;


    /**
     * Set performer
     *
     * @param \SD\UserBundle\Entity\User $performer
     * 
     * @return Task
     */
    public function setPerformer(\SD\UserBundle\Entity\User $performer = null)
    {
        $this->performer = $performer;

        return $this;
    }

    /**
     * Get performer
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getPerformer()
    {
        return $this->performer;
    }
}