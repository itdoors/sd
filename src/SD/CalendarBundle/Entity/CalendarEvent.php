<?php

namespace SD\CalendarBundle\Entity;

/**
 * CalendarEvent
 */
class CalendarEvent
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $eventName;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $fromdatetime;

    /**
     * @var \DateTime
     */
    private $todatetime;

    /**
     * @var boolean
     */
    private $isNotifi;

    /**
     * @var integer
     */
    private $notificationTime;

    /**
     * @var boolean
     */
    private $isFullDay;

    /**
     * @var string
     */
    private $additionalData;

    /**
     * @var \SD\CalendarBundle\Entity\CalendarEventType
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
     * Set eventName
     *
     * @param string $eventName
     *
     * @return CalendarEvent
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;

        return $this;
    }

    /**
     * Get eventName
     *
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return CalendarEvent
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
     * Set fromdatetime
     *
     * @param \DateTime $fromdatetime
     *
     * @return CalendarEvent
     */
    public function setFromdatetime($fromdatetime)
    {
        $this->fromdatetime = $fromdatetime;

        return $this;
    }

    /**
     * Get fromdatetime
     *
     * @return \DateTime
     */
    public function getFromdatetime()
    {
        return $this->fromdatetime;
    }

    /**
     * Set todatetime
     *
     * @param \DateTime $todatetime
     *
     * @return CalendarEvent
     */
    public function setTodatetime($todatetime)
    {
        $this->todatetime = $todatetime;

        return $this;
    }

    /**
     * Get todatetime
     *
     * @return \DateTime
     */
    public function getTodatetime()
    {
        return $this->todatetime;
    }

    /**
     * Set isNotifi
     *
     * @param boolean $isNotifi
     *
     * @return CalendarEvent
     */
    public function setIsNotifi($isNotifi)
    {
        $this->isNotifi = $isNotifi;

        return $this;
    }

    /**
     * Get isNotifi
     *
     * @return boolean
     */
    public function getIsNotifi()
    {
        return $this->isNotifi;
    }

    /**
     * Set notificationTime
     *
     * @param integer $notificationTime
     *
     * @return CalendarEvent
     */
    public function setNotificationTime($notificationTime)
    {
        $this->notificationTime = $notificationTime;

        return $this;
    }

    /**
     * Get notificationTime
     *
     * @return integer
     */
    public function getNotificationTime()
    {
        return $this->notificationTime;
    }

    /**
     * Set isFullDay
     *
     * @param boolean $isFullDay
     *
     * @return CalendarEvent
     */
    public function setIsFullDay($isFullDay)
    {
        $this->isFullDay = $isFullDay;

        return $this;
    }

    /**
     * Get isFullDay
     *
     * @return boolean
     */
    public function getIsFullDay()
    {
        return $this->isFullDay;
    }

    /**
     * Set additionalData
     *
     * @param string $additionalData
     *
     * @return CalendarEvent
     */
    public function setAdditionalData($additionalData)
    {
        $this->additionalData = $additionalData;

        return $this;
    }

    /**
     * Get additionalData
     *
     * @return string
     */
    public function getAdditionalData()
    {
        return $this->additionalData;
    }

    /**
     * Set type
     *
     * @param \SD\CalendarBundle\Entity\CalendarEventType $type
     *
     * @return CalendarEvent
     */
    public function setType(\SD\CalendarBundle\Entity\CalendarEventType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \SD\CalendarBundle\Entity\CalendarEventType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return CalendarEvent
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
