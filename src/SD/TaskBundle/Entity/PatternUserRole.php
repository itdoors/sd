<?php

namespace SD\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PatternUserRole
 */
class PatternUserRole
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $isViewed;

    /**
     * @var \SD\TaskBundle\Entity\TaskPattern
     */
    private $taskPattern;

    /**
     * @var \SD\TaskBundle\Entity\Role
     */
    private $role;

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
     * Set isViewed
     *
     * @param boolean $isViewed
     * @return PatternUserRole
     */
    public function setIsViewed($isViewed)
    {
        $this->isViewed = $isViewed;
    
        return $this;
    }

    /**
     * Get isViewed
     *
     * @return boolean 
     */
    public function getIsViewed()
    {
        return $this->isViewed;
    }

    /**
     * Set taskPattern
     *
     * @param \SD\TaskBundle\Entity\TaskPattern $taskPattern
     * @return PatternUserRole
     */
    public function setTaskPattern(\SD\TaskBundle\Entity\TaskPattern $taskPattern = null)
    {
        $this->taskPattern = $taskPattern;
    
        return $this;
    }

    /**
     * Get taskPattern
     *
     * @return \SD\TaskBundle\Entity\TaskPattern 
     */
    public function getTaskPattern()
    {
        return $this->taskPattern;
    }

    /**
     * Set role
     *
     * @param \SD\TaskBundle\Entity\Role $role
     * @return PatternUserRole
     */
    public function setRole(\SD\TaskBundle\Entity\Role $role = null)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return \SD\TaskBundle\Entity\Role 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * @return PatternUserRole
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