<?php

namespace ITDoors\OperBundle\Entity;

use Doctrine\ORM\Query;

/**
 * CommentOrganizer
 */
class CommentOrganizer
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
    private $value;

    /**
     * @var string
     */
    private $additionField;

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
     * Set createDatetime
     *
     * @param \DateTime $createDatetime
     *
     * @return CommentOrganizer
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
     * Set value
     *
     * @param string $value
     *
     * @return CommentOrganizer
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
     * Set additionField
     *
     * @param string $additionField
     *
     * @return CommentOrganizer
     */
    public function setAdditionField($additionField)
    {
        $this->additionField = $additionField;

        return $this;
    }

    /**
     * Get additionField
     *
     * @return string 
     */
    public function getAdditionField()
    {
        return $this->additionField;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return CommentOrganizer
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
     * @var \ITDoors\OperBundle\Entity\OperOrganizer
     */
    private $organizer;


    /**
     * Set organizer
     *
     * @param \ITDoors\OperBundle\Entity\OperOrganizer $organizer
     *
     * @return CommentOrganizer
     */
    public function setOrganizer(\ITDoors\OperBundle\Entity\OperOrganizer $organizer = null)
    {
        $this->organizer = $organizer;

        return $this;
    }

    /**
     * Get organizer
     *
     * @return \ITDoors\OperBundle\Entity\OperOrganizer 
     */
    public function getOrganizer()
    {
        return $this->organizer;
    }
}
