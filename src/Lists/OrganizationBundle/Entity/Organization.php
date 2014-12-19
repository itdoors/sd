<?php

namespace Lists\OrganizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Organization
 */
class Organization
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    private $mpk;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $contacts;

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
     * Set mpk
     *
     * @param string $mpk
     *
     * @return Organization
     */
    public function setMpk ($mpk)
    {
        $this->mpk = $mpk;

        return $this;
    }
    /**
     * Get mpk
     *
     * @return string
     */
    public function getMpk ()
    {
        return $this->mpk;
    }
    /**
     * Set name
     *
     * @param string $name
     *
     * @return Organization
     */
    public function setName ($name)
    {
        $this->name = $name;

        return $this;
    }
    /**
     * Get name
     *
     * @return string
     */
    public function getName ()
    {
        return $this->name;
    }
    /**
     * Set address
     *
     * @param string $address
     *
     * @return Organization
     */
    public function setAddress ($address)
    {
        $this->address = $address;

        return $this;
    }
    /**
     * Get address
     *
     * @return string
     */
    public function getAddress ()
    {
        return $this->address;
    }
    /**
     * Set contacts
     *
     * @param string $contacts
     *
     * @return Organization
     */
    public function setContacts ($contacts)
    {
        $this->contacts = $contacts;

        return $this;
    }
    /**
     * Get contacts
     *
     * @return string
     */
    public function getContacts ()
    {
        return $this->contacts;
    }

    /**
     * @var \Lists\CityBundle\Entity\City
     */
    private $city;

    /**
     * Set city
     *
     * @param \Lists\CityBundle\Entity\City $city
     *
     * @return Organization
     */
    public function setCity (\Lists\CityBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }
    /**
     * Get city
     *
     * @return \Lists\CityBundle\Entity\City
     */
    public function getCity ()
    {
        return $this->city;
    }

    /**
     * @var \Lists\LookupBundle\Entity\Lookup
     */
    private $scope;

    /**
     * Set scope
     *
     * @param \Lists\LookupBundle\Entity\Lookup $scope
     *
     * @return Organization
     */
    public function setScope (\Lists\LookupBundle\Entity\Lookup $scope = null)
    {
        $this->scope = $scope;

        return $this;
    }
    /**
     * Get scope
     *
     * @return \Lists\LookupBundle\Entity\Lookup
     */
    public function getScope ()
    {
        return $this->scope;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct ()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setIsSmeta(false);
    }
    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers ()
    {
        return $this->users;
    }

    /**
     * @var string
     */
    private $shortname;

    /**
     * @var boolean
     */
    private $isSmeta;

    /**
     * @var string
     */
    private $mailingAddress;

    /**
     * @var string
     */
    private $rs;

    /**
     * @var string
     */
    private $edrpou;

    /**
     * @var string
     */
    private $inn;

    /**
     * @var string
     */
    private $certificate;

    /**
     * @var string
     */
    private $shortDescription;

    /**
     * @var string
     */
    private $site;

    /**
     * Set shortname
     *
     * @param string $shortname
     *
     * @return Organization
     */
    public function setShortname ($shortname)
    {
        $this->shortname = $shortname;

        return $this;
    }
    /**
     * Get shortname
     *
     * @return string
     */
    public function getShortname ()
    {
        return $this->shortname;
    }
    /**
     * Set isSmeta
     *
     * @param boolean $isSmeta
     *
     * @return Organization
     */
    public function setIsSmeta ($isSmeta)
    {
        $this->isSmeta = $isSmeta;

        return $this;
    }
    /**
     * Get isSmeta
     *
     * @return boolean
     */
    public function getIsSmeta ()
    {
        return $this->isSmeta;
    }
    /**
     * Set mailingAddress
     *
     * @param string $mailingAddress
     *
     * @return Organization
     */
    public function setMailingAddress ($mailingAddress)
    {
        $this->mailingAddress = $mailingAddress;

        return $this;
    }
    /**
     * Get mailingAddress
     *
     * @return string
     */
    public function getMailingAddress ()
    {
        return $this->mailingAddress;
    }
    /**
     * Set rs
     *
     * @param string $rs
     *
     * @return Organization
     */
    public function setRs ($rs)
    {
        $this->rs = $rs;

        return $this;
    }
    /**
     * Get rs
     *
     * @return string
     */
    public function getRs ()
    {
        return $this->rs;
    }
    /**
     * Set edrpou
     *
     * @param string $edrpou
     *
     * @return Organization
     */
    public function setEdrpou ($edrpou)
    {
        $this->edrpou = $edrpou;

        return $this;
    }
    /**
     * Get edrpou
     *
     * @return string
     */
    public function getEdrpou ()
    {
        return $this->edrpou;
    }
    /**
     * Set inn
     *
     * @param string $inn
     *
     * @return Organization
     */
    public function setInn ($inn)
    {
        $this->inn = $inn;

        return $this;
    }
    /**
     * Get inn
     *
     * @return string
     */
    public function getInn ()
    {
        return $this->inn;
    }
    /**
     * Set certificate
     *
     * @param string $certificate
     *
     * @return Organization
     */
    public function setCertificate ($certificate)
    {
        $this->certificate = $certificate;

        return $this;
    }
    /**
     * Get certificate
     *
     * @return string
     */
    public function getCertificate ()
    {
        return $this->certificate;
    }
    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     *
     * @return Organization
     */
    public function setShortDescription ($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }
    /**
     * Get shortDescription
     *
     * @return string
     */
    public function getShortDescription ()
    {
        return $this->shortDescription;
    }
    /**
     * Set site
     *
     * @param string $site
     *
     * @return Organization
     */
    public function setSite ($site)
    {
        $this->site = $site;

        return $this;
    }
    /**
     * Get site
     *
     * @return string
     */
    public function getSite ()
    {
        return $this->site;
    }

    /**
     * @var \Lists\OrganizationBundle\Entity\OrganizationType
     */
    private $organizationType;

    /**
     * Set organizationType
     *
     * @param \Lists\OrganizationBundle\Entity\OrganizationType $organizationType
     *
     * @return Organization
     */
    public function setOrganizationType (\Lists\OrganizationBundle\Entity\OrganizationType $organizationType = null)
    {
        $this->organizationType = $organizationType;

        return $this;
    }
    /**
     * Get organizationType
     *
     * @return \Lists\OrganizationBundle\Entity\OrganizationType
     */
    public function getOrganizationType ()
    {
        return $this->organizationType;
    }
    /**
     * @return string
     */
    public function __toString ()
    {
        return $this->getName();
    }
    /**
     * Add users
     *
     * @param \SD\UserBundle\Entity\User $users
     *
     * @return Organization
     */
    public function addUser (\SD\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }
    /**
     * Remove users
     *
     * @param \SD\UserBundle\Entity\User $users
     */
    public function removeUser (\SD\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * @var integer
     */
    private $city_id;

    /**
     * @var integer
     */
    private $scope_id;

    /**
     * Set city_id
     *
     * @param integer $cityId
     *
     * @return Organization
     */
    public function setCityId ($cityId)
    {
        // @codingStandardsIgnoreStart
        $this->city_id = $cityId;
        // @codingStandardsIgnoreEnd
        return $this;
    }
    /**
     * Get city_id
     *
     * @return integer
     */
    public function getCityId ()
    {
        // @codingStandardsIgnoreStart
        return $this->city_id;
        // @codingStandardsIgnoreEnd
    }
    /**
     * Set scope_id
     *
     * @param integer $scopeId
     *
     * @return Organization
     */
    public function setScopeId ($scopeId)
    {
        // @codingStandardsIgnoreStart
        $this->scope_id = $scopeId;
        // @codingStandardsIgnoreEnd
        return $this;
    }
    /**
     * Get scope_id
     *
     * @return integer
     */
    public function getScopeId ()
    {
        // @codingStandardsIgnoreStart
        return $this->scope_id;
        // @codingStandardsIgnoreEnd
    }

    /**
     * @var integer
     */
    private $organization_type_id;

    /**
     * Set organization_type_id
     *
     * @param integer $organizationTypeId
     *
     * @return Organization
     */
    public function setOrganizationTypeId ($organizationTypeId)
    {
        // @codingStandardsIgnoreStart
        $this->organization_type_id = $organizationTypeId;
        // @codingStandardsIgnoreEnd
        return $this;
    }
    /**
     * Get organization_type_id
     *
     * @return integer
     */
    public function getOrganizationTypeId ()
    {
        // @codingStandardsIgnoreStart
        return $this->organization_type_id;
        // @codingStandardsIgnoreEnd
    }

    /**
     * @var \DateTime
     */
    private $createdatetime;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $creator;

    /**
     * Set createdatetime
     *
     * @param \DateTime $createdatetime
     *
     * @return Organization
     */
    public function setCreatedatetime ($createdatetime)
    {
        $this->createdatetime = $createdatetime;

        return $this;
    }
    /**
     * Get createdatetime
     *
     * @return \DateTime
     */
    public function getCreatedatetime ()
    {
        return $this->createdatetime;
    }
    /**
     * Set creator
     *
     * @param \SD\UserBundle\Entity\User $creator
     *
     * @return Organization
     */
    public function setCreator (\SD\UserBundle\Entity\User $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }
    /**
     * Get creator
     *
     * @return \SD\UserBundle\Entity\User
     */
    public function getCreator ()
    {
        return $this->creator;
    }
    /**
     * @ORM\PrePersist
     */
    public function doOnPrePersist ()
    {
        if (!$this->getId()) {
            $this->setCreatedatetime(new \DateTime());
        }
    }

    /**
     * @var string
     */
    private $physicalAddress;

    /**
     * @var string
     */
    private $phone;

    /**
     * Set physicalAddress
     *
     * @param string $physicalAddress
     *
     * @return Organization
     */
    public function setPhysicalAddress ($physicalAddress)
    {
        $this->physicalAddress = $physicalAddress;

        return $this;
    }
    /**
     * Get physicalAddress
     *
     * @return string
     */
    public function getPhysicalAddress ()
    {
        return $this->physicalAddress;
    }
    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Organization
     */
    public function setPhone ($phone)
    {
        $this->phone = $phone;

        return $this;
    }
    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone ()
    {
        return $this->phone;
    }

    /**
     * @var \Lists\OrganizationBundle\Entity\OrganizationGroup
     */
    private $group;

    /**
     * Set group
     *
     * @param \Lists\OrganizationBundle\Entity\OrganizationGroup $group
     *
     * @return Organization
     */
    public function setGroup (\Lists\OrganizationBundle\Entity\OrganizationGroup $group = null)
    {
        $this->group = $group;

        return $this;
    }
    /**
     * Get group
     *
     * @return \Lists\OrganizationBundle\Entity\OrganizationGroup
     */
    public function getGroup ()
    {
        return $this->group;
    }

    /**
     * @var integer
     */
    private $group_id;

    /**
     * Set group_id
     *
     * @param integer $groupId
     *
     * @return Organization
     */
    public function setGroupId ($groupId)
    {
        // @codingStandardsIgnoreStart
        $this->group_id = $groupId;
        // @codingStandardsIgnoreEnd
        return $this;
    }
    /**
     * Get group_id
     *
     * @return integer
     */
    public function getGroupId ()
    {
        // @codingStandardsIgnoreStart
        return $this->group_id;
        // @codingStandardsIgnoreEnd
    }
    /**
     * @return array
     */
    public function __sleep ()
    {
        return array (
            'id',
            'name'
        );
    }

    /**
     * @var integer
     */
    private $parent_id;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $parent;

    /**
     * Set parent_id
     *
     * @param integer $parentId
     *
     * @return Organization
     */
    public function setParentId ($parentId)
    {
        // @codingStandardsIgnoreStart
        $this->parent_id = $parentId;
        // @codingStandardsIgnoreEnd
        return $this;
    }
    /**
     * Get parent_id
     *
     * @return integer
     */
    public function getParentId ()
    {
        // @codingStandardsIgnoreStart
        return $this->parent_id;
        // @codingStandardsIgnoreEnd
    }
    /**
     * Set parent
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $parent
     *
     * @return Organization
     */
    public function setParent (\Lists\OrganizationBundle\Entity\Organization $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }
    /**
     * Get parent
     *
     * @return \Lists\OrganizationBundle\Entity\Organization
     */
    public function getParent ()
    {
        return $this->parent;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $children;

    /**
     * Add children
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $children
     *
     * @return Organization
     */
    public function addChildren (\Lists\OrganizationBundle\Entity\Organization $children)
    {
        $this->children[] = $children;

        return $this;
    }
    /**
     * Remove children
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $children
     */
    public function removeChildren (\Lists\OrganizationBundle\Entity\Organization $children)
    {
        $this->children->removeElement($children);
    }
    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren ()
    {
        return $this->children;
    }

    /**
     * @var integer
     */
    private $organizationSignId;

    /**
     * Set organizationSignId
     *
     * @param integer $organizationSignId
     *
     * @return Organization
     */
    public function setOrganizationSignId ($organizationSignId)
    {
        $this->organizationSignId = $organizationSignId;

        return $this;
    }
    /**
     * Get organizationSignId
     *
     * @return integer 
     */
    public function getOrganizationSignId ()
    {
        return $this->organizationSignId;
    }

    /**
     * @var \Lists\LookupBundle\Entity\Lookup
     */
    private $lookup;

    /**
     * Set lookup
     *
     * @param \Lists\LookupBundle\Entity\Lookup $lookup
     *
     * @return Organization
     */
    public function setLookup (\Lists\LookupBundle\Entity\Lookup $lookup = null)
    {
        $this->lookup = $lookup;

        return $this;
    }
    /**
     * Get lookup
     *
     * @return \Lists\LookupBundle\Entity\Lookup 
     */
    public function getLookup ()
    {
        return $this->lookup;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $organizationUsers;

    /**
     * Add organizationUsers
     *
     * @param \Lists\OrganizationBundle\Entity\OrganizationUser $organizationUsers
     * 
     * @return Organization
     */
    public function addOrganizationUser (\Lists\OrganizationBundle\Entity\OrganizationUser $organizationUsers)
    {
        $this->organizationUsers[] = $organizationUsers;

        return $this;
    }
    /**
     * Remove organizationUsers
     *
     * @param \Lists\OrganizationBundle\Entity\OrganizationUser $organizationUsers
     */
    public function removeOrganizationUser (\Lists\OrganizationBundle\Entity\OrganizationUser $organizationUsers)
    {
        $this->organizationUsers->removeElement($organizationUsers);
    }
    /**
     * Get organizationUsers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrganizationUsers ()
    {
        return $this->organizationUsers;
    }

    /**
     * @var \DateTime
     */
    private $deletedAt;

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * 
     * @return Organization
     */
    public function setDeletedAt ($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @var \Lists\OrganizationBundle\Entity\OrganizationOwnership
     */
    private $ownership;

    /**
     * Set ownership
     *
     * @param \Lists\OrganizationBundle\Entity\OrganizationOwnership $ownership
     * 
     * @return Organization
     */
    public function setOwnership (\Lists\OrganizationBundle\Entity\OrganizationOwnership $ownership = null)
    {
        $this->ownership = $ownership;

        return $this;
    }
    /**
     * Get deletedAt
     *
     * @return \DateTime 
     */
    public function getDeletedAt ()
    {
        return $this->deletedAt;
    }
    /**
     * Get ownership
     *
     * @return \Lists\OrganizationBundle\Entity\OrganizationOwnership 
     */
    public function getOwnership ()
    {
        return $this->ownership;
    }

    /**
     * @var integer
     */
    private $ownershipId;

    /**
     * Set ownershipId
     *
     * @param integer $ownershipId
     * 
     * @return Organization
     */
    public function setOwnershipId ($ownershipId)
    {
        $this->ownershipId = $ownershipId;

        return $this;
    }
    /**
     * Get ownershipId
     *
     * @return integer 
     */
    public function getOwnershipId ()
    {
        return $this->ownershipId;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $organizationsigns;


    /**
     * Add organizationsigns
     *
     * @param \Lists\LookupBundle\Entity\Lookup $organizationsigns
     * 
     * @return Organization
     */
    public function addOrganizationsign(\Lists\LookupBundle\Entity\Lookup $organizationsigns)
    {
        $this->organizationsigns[] = $organizationsigns;

        return $this;
    }

    /**
     * Remove organizationsigns
     *
     * @param \Lists\LookupBundle\Entity\Lookup $organizationsigns
     */
    public function removeOrganizationsign(\Lists\LookupBundle\Entity\Lookup $organizationsigns)
    {
        $this->organizationsigns->removeElement($organizationsigns);
    }

    /**
     * Get organizationsigns
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrganizationsigns()
    {
        return $this->organizationsigns;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $departments;


    /**
     * Add departments
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $departments
     * 
     * @return Organization
     */
    public function addDepartmen(\Lists\DepartmentBundle\Entity\Departments $departments)
    {
        $this->departments[] = $departments;

        return $this;
    }

    /**
     * Remove departments
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $departments
     */
    public function removeDepartmen(\Lists\DepartmentBundle\Entity\Departments $departments)
    {
        $this->departments->removeElement($departments);
    }

    /**
     * Get departments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDepartments()
    {
        return $this->departments;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $currentAccounts;


    /**
     * Add departments
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $departments
     * @return Organization
     */
    public function addDepartment(\Lists\DepartmentBundle\Entity\Departments $departments)
    {
        $this->departments[] = $departments;
    
        return $this;
    }

    /**
     * Remove departments
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $departments
     */
    public function removeDepartment(\Lists\DepartmentBundle\Entity\Departments $departments)
    {
        $this->departments->removeElement($departments);
    }

    /**
     * Add currentAccounts
     *
     * @param \Lists\OrganizationBundle\Entity\OrganizationCurrentAccount $currentAccounts
     * @return Organization
     */
    public function addCurrentAccount(\Lists\OrganizationBundle\Entity\OrganizationCurrentAccount $currentAccounts)
    {
        $this->currentAccounts[] = $currentAccounts;
    
        return $this;
    }

    /**
     * Remove currentAccounts
     *
     * @param \Lists\OrganizationBundle\Entity\OrganizationCurrentAccount $currentAccounts
     */
    public function removeCurrentAccount(\Lists\OrganizationBundle\Entity\OrganizationCurrentAccount $currentAccounts)
    {
        $this->currentAccounts->removeElement($currentAccounts);
    }

    /**
     * Get currentAccounts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentAccounts()
    {
        return $this->currentAccounts;
    }
}