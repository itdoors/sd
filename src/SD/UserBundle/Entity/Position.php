<?php

namespace SD\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Position
 */
class Position
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
     * @return Position
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $groups;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add groups
     *
     * @param \SD\UserBundle\Entity\Group $group
     * 
     * @return Position
     */
    public function addGroup(\SD\UserBundle\Entity\Group $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \SD\UserBundle\Entity\Group $groups
     */
    public function removeGroup(\SD\UserBundle\Entity\Group $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Set groups
     *
     * @param \Doctrine\Common\Collections\Collection $groups
     *
     * @return Position
     */
    public function setGroups(\Doctrine\Common\Collections\Collection $groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroups()
    {
        return $this->groups;
    }
    /**
     * __toString
     * 
     * @return string
     */
    public function __toString ()
    {
        return $this->getName();
    }
}
