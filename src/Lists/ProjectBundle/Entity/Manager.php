<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Manager
 */
class Manager
{
    /**
     * 
     * @return string
     */
    public function __toString ()
    {
        return (string) $this->getUser();
    }
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $part;

    /**
     * @var \Lists\ProjectBundle\Entity\Project
     */
    private $project;

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
     * @return Manager
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
     * Set project
     *
     * @param \Lists\ProjectBundle\Entity\Project $project
     * @return Manager
     */
    public function setProject(\Lists\ProjectBundle\Entity\Project $project = null)
    {
        $this->project = $project;
    
        return $this;
    }

    /**
     * Get project
     *
     * @return \Lists\ProjectBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * @return Manager
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