<?php
// src/SD/UserBundle/Entity/User.php

namespace SD\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 */
class User extends BaseUser
{
  public function __construct()
  {
    parent::__construct();
  }
    /**
     * @var integer
     */
  protected $id;

  /**
   * @var \Doctrine\Common\Collections\Collection
   */
  protected $groups;


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
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroups()
    {
        return $this->groups;
    }
}