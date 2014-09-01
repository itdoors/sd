<?php

namespace SD\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskUserRole
 */
class TaskUserRole
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \SD\TaskBundle\Entity\Task
     */
    private $task;

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
     * Set task
     *
     * @param \SD\TaskBundle\Entity\Task $task
     *
     * @return TaskUserRole
     */
    public function setTask(\SD\TaskBundle\Entity\Task $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return \SD\TaskBundle\Entity\Task 
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Set role
     *
     * @param \SD\TaskBundle\Entity\Role $role
     *
     * @return TaskUserRole
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
     *
     * @return TaskUserRole
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
    private $isViewed;


    /**
     * Set isViewed
     *
     * @param boolean $isViewed
     *
     * @return TaskUserRole
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
}