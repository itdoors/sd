<?php

/**
 * User model class
 *
 * @author Pavel Pecheny ppecheny@gmail.com
 */

namespace SD\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 */
class User extends BaseUser
{
    /**
     * __construct()
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $middleName;

    /**
     * @var string
     */
    private $position;

    /**
     * @var boolean
     */
    private $isBlocked;

    /**
     * @var boolean
     */
    private $isFired;

    /**
     * @var \DateTime
     */
    private $birthday;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $groups;

    /**
     * Set id
     *
     * @param string $id
     *
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     *
     * @return User
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return User
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set isBlocked
     *
     * @param boolean $isBlocked
     *
     * @return User
     */
    public function setIsBlocked($isBlocked)
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }

    /**
     * Get isBlocked
     *
     * @return boolean
     */
    public function getIsBlocked()
    {
        return $this->isBlocked;
    }

    /**
     * Set isFired
     *
     * @param boolean $isFired
     *
     * @return User
     */
    public function setIsFired($isFired)
    {
        $this->isFired = $isFired;

        return $this;
    }

    /**
     * Get isFired
     *
     * @return boolean
     */
    public function getIsFired()
    {
        return $this->isFired;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Returns user full name
     *
     * @return string
     */
    public function getFullname()
    {
        return (string) sprintf('%s %s', $this->getLastName(), $this->getFirstName());
    }

    /**
     * ToString magic method
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getFullname();
    }

    /**
     * @var \SD\UserBundle\Entity\Stuff
     */
    private $stuff;

    /**
     * Set stuff
     *
     * @param \SD\UserBundle\Entity\Stuff $stuff
     *
     * @return User
     */
    public function setStuff(\SD\UserBundle\Entity\Stuff $stuff = null)
    {
        $this->stuff = $stuff;

        return $this;
    }

    /**
     * Get stuff
     *
     * @return \SD\UserBundle\Entity\Stuff
     */
    public function getStuff()
    {
        return $this->stuff;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $teams;

    /**
     * Add teams
     *
     * @param \Lists\TeamBundle\Entity\Team $teams
     *
     * @return User
     */
    public function addTeam(\Lists\TeamBundle\Entity\Team $teams)
    {
        $this->teams[] = $teams;

        return $this;
    }

    /**
     * Remove teams
     *
     * @param \Lists\TeamBundle\Entity\Team $teams
     */
    public function removeTeam(\Lists\TeamBundle\Entity\Team $teams)
    {
        $this->teams->removeElement($teams);
    }

    /**
     * Get teams
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @var string
     */
    private $photo;

    /**
     * Set photo
     *
     * @param string $photo
     * 
     * @return User
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $usercontactinfo;

    /**
     * Add usercontactinfo
     *
     * @param \SD\UserBundle\Entity\Contactinfo $usercontactinfo
     * 
     * @return User
     */
    public function addUsercontactinfo(\SD\UserBundle\Entity\Contactinfo $usercontactinfo)
    {
        $this->usercontactinfo[] = $usercontactinfo;

        return $this;
    }

    /**
     * Remove usercontactinfo
     *
     * @param \SD\UserBundle\Entity\Contactinfo $usercontactinfo
     */
    public function removeUsercontactinfo(\SD\UserBundle\Entity\Contactinfo $usercontactinfo)
    {
        $this->usercontactinfo->removeElement($usercontactinfo);
    }

    /**
     * Get usercontactinfo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsercontactinfo()
    {
        return $this->usercontactinfo;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $handlingUsers;


    /**
     * Add handlingUsers
     *
     * @param \Lists\HandlingBundle\Entity\HandlingUser $handlingUsers
     * 
     * @return User
     */
    public function addHandlingUser(\Lists\HandlingBundle\Entity\HandlingUser $handlingUsers)
    {
        $this->handlingUsers[] = $handlingUsers;

        return $this;
    }

    /**
     * Remove handlingUsers
     *
     * @param \Lists\HandlingBundle\Entity\HandlingUser $handlingUsers
     */
    public function removeHandlingUser(\Lists\HandlingBundle\Entity\HandlingUser $handlingUsers)
    {
        $this->handlingUsers->removeElement($handlingUsers);
    }

    /**
     * Get handlingUsers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHandlingUsers()
    {
        return $this->handlingUsers;
    }

    private $organizationUsers;


    /**
     * Add organizationUsers
     *
     * @param \Lists\OrganizationBundle\Entity\OrganizationUser $organizationUsers
     * 
     * @return User
     */
    public function addOrganizationUser(\Lists\OrganizationBundle\Entity\OrganizationUser $organizationUsers)
    {
        $this->organizationUsers[] = $organizationUsers;

        return $this;
    }

    /**
     * Remove organizationUsers
     *
     * @param \Lists\OrganizationBundle\Entity\OrganizationUser $organizationUsers
     */
    public function removeOrganizationUser(\Lists\OrganizationBundle\Entity\OrganizationUser $organizationUsers)
    {
        $this->organizationUsers->removeElement($organizationUsers);
    }

    /**
     * Get organizationUsers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrganizationUsers()
    {
        return $this->organizationUsers;
    }
}
