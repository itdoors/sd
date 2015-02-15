<?php

namespace SD\UserBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;

/**
 * Group
 */
class Group extends BaseGroup
{
    /**
     * @var integer
     */
    protected $id;

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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;

    /**
     * __construct
     * 
     * @param string $name
     * @param array  $roles
     */
    public function __construct($name, $roles = array())
    {
        parent::__construct($name, $roles);
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add users
     *
     * @param \SD\UserBundle\Entity\User $users
     * 
     * @return Group
     */
    public function addUser(\SD\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \SD\UserBundle\Entity\User $users
     */
    public function removeUser(\SD\UserBundle\Entity\User $users)
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
}
