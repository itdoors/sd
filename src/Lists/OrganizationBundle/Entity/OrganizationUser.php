<?php

namespace Lists\OrganizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrganizationUser
 */
class OrganizationUser
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $organizationId;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $organization;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $user;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId ()
    {
        return $this->id;
    }
    /**
     * Set organizationId
     *
     * @param integer $organizationId
     * 
     * @return OrganizationUser
     */
    public function setOrganizationId ($organizationId)
    {
        $this->organizationId = $organizationId;

        return $this;
    }
    /**
     * Get organizationId
     *
     * @return integer 
     */
    public function getOrganizationId ()
    {
        return $this->organizationId;
    }
    /**
     * Set userId
     *
     * @param integer $userId
     * 
     * @return OrganizationUser
     */
    public function setUserId ($userId)
    {
        $this->userId = $userId;

        return $this;
    }
    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId ()
    {
        return $this->userId;
    }
    /**
     * Set organization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organization
     * 
     * @return OrganizationUser
     */
    public function setOrganization (\Lists\OrganizationBundle\Entity\Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }
    /**
     * Get organization
     *
     * @return \Lists\OrganizationBundle\Entity\Organization 
     */
    public function getOrganization ()
    {
        return $this->organization;
    }
    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * 
     * @return OrganizationUser
     */
    public function setUser (\SD\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }
    /**
     * Get user
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getUser ()
    {
        return $this->user;
    }
    /**
     * @ORM\PrePersist
     */
    public function doOnPrePersist ()
    {
        // Add your code here
    }

    /**
     * @var integer
     */
    private $roleId;

    /**
     * @var \Lists\LookupBundle\Entity\Lookup
     */
    private $role;

    /**
     * Set roleId
     *
     * @param integer $roleId
     * 
     * @return OrganizationUser
     */
    public function setRoleId ($roleId)
    {
        $this->roleId = $roleId;

        return $this;
    }
    /**
     * Get roleId
     *
     * @return integer 
     */
    public function getRoleId ()
    {
        return $this->roleId;
    }
    /**
     * Set role
     *
     * @param \Lists\LookupBundle\Entity\Lookup $role
     * 
     * @return OrganizationUser
     */
    public function setRole (\Lists\LookupBundle\Entity\Lookup $role = null)
    {
        $this->role = $role;

        return $this;
    }
    /**
     * Get role
     *
     * @return \Lists\LookupBundle\Entity\Lookup 
     */
    public function getRole ()
    {
        return $this->role;
    }
}
