<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HandlingUser
 */
class HandlingUser
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $part;

    /**
     * @var \Lists\HandlingBundle\Entity\Handling
     */
    private $handling;

    /**
     * @var \Lists\LookupBundle\Entity\Lookup
     */
    private $lookup;

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
     * Set part
     *
     * @param integer $part
     * 
     * @return HandlingUser
     */
    public function setPart($part)
    {
        $this->part = $part;

        return $this;
    }

    /**
     * Get part
     *
     * @return integer 
     */
    public function getPart()
    {
        return $this->part;
    }

    /**
     * Set handling
     *
     * @param \Lists\HandlingBundle\Entity\Handling $handling
     * 
     * @return HandlingUser
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
     * Set lookup
     *
     * @param \Lists\LookupBundle\Entity\Lookup $lookup
     * 
     * @return HandlingUser
     */
    public function setLookup(\Lists\LookupBundle\Entity\Lookup $lookup = null)
    {
        $this->lookup = $lookup;

        return $this;
    }

    /**
     * Get lookup
     *
     * @return \Lists\LookupBundle\Entity\Lookup 
     */
    public function getLookup()
    {
        return $this->lookup;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * 
     * @return HandlingUser
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add users
     *
     * @param \Lists\HandlingBundle\Entity\Handling $users
     * 
     * @return HandlingUser
     */
    public function addUser(\Lists\HandlingBundle\Entity\Handling $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Lists\HandlingBundle\Entity\Handling $users
     */
    public function removeUser(\Lists\HandlingBundle\Entity\Handling $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
    /**
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
    private $lookupId;

    /**
     * @var integer
     */
    private $handlingId;


    /**
     * Set userId
     *
     * @param integer $userId
     * 
     * @return HandlingUser
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
     * Set lookupId
     *
     * @param integer $lookupId
     * 
     * @return HandlingUser
     */
    public function setLookupId($lookupId)
    {
        $this->lookupId = $lookupId;

        return $this;
    }

    /**
     * Get lookupId
     *
     * @return integer 
     */
    public function getLookupId()
    {
        return $this->lookupId;
    }

    /**
     * Set handlingId
     *
     * @param integer $handlingId
     * 
     * @return HandlingUser
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
}