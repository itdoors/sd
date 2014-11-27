<?php

namespace SD\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserActivityRecord
 */
class UserActivityRecord
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var SD\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \DateTime
     */
    private $lastActivity;


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
     * Set user
     *
     * @param SD\UserBundle\Entity\User $user
     * 
     * @return UserActivityRecord
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return SD\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set lastActivity
     *
     * @param \DateTime $lastActivity
     * 
     * @return UserActivityRecord
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    /**
     * Get lastActivity
     *
     * @return \DateTime 
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }
}
