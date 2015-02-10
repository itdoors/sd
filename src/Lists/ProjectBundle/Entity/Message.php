<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 */
class Message
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createDatetime;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $eventDatetime;

    /**
     * @var \DateTime
     */
    private $deletedDatetime;

    /**
     * @var \Lists\ProjectBundle\Entity\Project
     */
    private $project;

    /**
     * @var \Lists\ProjectBundle\Entity\MessageType
     */
    private $type;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \Lists\ContactBundle\Entity\ModelContact
     */
    private $contact;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId ()
    {
        return $this->id;
    }
    /**
     * Set createDatetime
     *
     * @param \DateTime $createDatetime
     *
     * @return Message
     */
    public function setCreateDatetime ($createDatetime)
    {
        $this->createDatetime = $createDatetime;

        return $this;
    }
    /**
     * Get createDatetime
     *
     * @return \DateTime 
     */
    public function getCreateDatetime ()
    {
        return $this->createDatetime;
    }
    /**
     * Set description
     *
     * @param string $description
     *
     * @return Message
     */
    public function setDescription ($description)
    {
        $this->description = $description;

        return $this;
    }
    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription ()
    {
        return $this->description;
    }
    /**
     * Set eventDatetime
     *
     * @param \DateTime $eventDatetime
     *
     * @return Message
     */
    public function setEventDatetime ($eventDatetime)
    {
        $this->eventDatetime = $eventDatetime;

        return $this;
    }
    /**
     * Get eventDatetime
     *
     * @return \DateTime 
     */
    public function getEventDatetime ()
    {
        return $this->eventDatetime;
    }
    /**
     * Set deletedDatetime
     *
     * @param \DateTime $deletedDatetime
     *
     * @return Message
     */
    public function setDeletedDatetime ($deletedDatetime)
    {
        $this->deletedDatetime = $deletedDatetime;

        return $this;
    }
    /**
     * Get deletedDatetime
     *
     * @return \DateTime 
     */
    public function getDeletedDatetime ()
    {
        return $this->deletedDatetime;
    }
    /**
     * Set project
     *
     * @param \Lists\ProjectBundle\Entity\Project $project
     *
     * @return Message
     */
    public function setProject (\Lists\ProjectBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }
    /**
     * Get project
     *
     * @return \Lists\ProjectBundle\Entity\Project 
     */
    public function getProject ()
    {
        return $this->project;
    }
    /**
     * Set type
     *
     * @param \Lists\ProjectBundle\Entity\MessageType $type
     *
     * @return Message
     */
    public function setType (\Lists\ProjectBundle\Entity\MessageType $type = null)
    {
        $this->type = $type;

        return $this;
    }
    /**
     * Get type
     *
     * @return \Lists\ProjectBundle\Entity\MessageType 
     */
    public function getType ()
    {
        return $this->type;
    }
    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return Message
     */
    public function setUser (\SD\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }
    /**
     * Get user
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getUser ()
    {
        return $this->user;
    }
    /**
     * Set contact
     *
     * @param \Lists\ContactBundle\Entity\ModelContact $contact
     *
     * @return Message
     */
    public function setContact (\Lists\ContactBundle\Entity\ModelContact $contact = null)
    {
        $this->contact = $contact;

        return $this;
    }
    /**
     * Get contact
     *
     * @return \Lists\ContactBundle\Entity\ModelContact 
     */
    public function getContact ()
    {
        return $this->contact;
    }
}