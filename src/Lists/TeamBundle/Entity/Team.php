<?php

namespace Lists\TeamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Team
 */
class Team
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
    private $descriprion;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $owner;

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
     *
     * @return Team
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
     * Set descriprion
     *
     * @param string $descriprion
     *
     * @return Team
     */
    public function setDescriprion($descriprion)
    {
        $this->descriprion = $descriprion;

        return $this;
    }

    /**
     * Get descriprion
     *
     * @return string
     */
    public function getDescriprion()
    {
        return $this->descriprion;
    }

    /**
     * Set owner
     *
     * @param \SD\UserBundle\Entity\User $owner
     *
     * @return Team
     */
    public function setOwner(\SD\UserBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \SD\UserBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Add users
     *
     * @param \SD\UserBundle\Entity\User $users
     *
     * @return Team
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
    /**
     * @var string
     */
    private $description;

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Team
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
     * @ORM\PrePersist
     */
    public function doOnPrePersist()
    {
    }
}
