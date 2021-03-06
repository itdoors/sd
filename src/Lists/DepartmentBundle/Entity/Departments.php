<?php

namespace Lists\DepartmentBundle\Entity;

use ITDoors\HelperBundle\Classes\HiddenFields;

/**
 * Departments
 */
class Departments extends HiddenFields
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
    private $fullname;

    /**
     * @var string
     */
    private $address;

    /**
     * @var float
     */
    private $square;

    /**
     * @var boolean
     */
    private $isdeleted;

    /**
     * @var string
     */
    private $addedField;

    /**
     * @var \DateTime
     */
    private $statusDate;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $coordinates;

    /**
     * @var integer
     */
    private $organizationId;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $mpks;

    /**
     * @var \Lists\CityBundle\Entity\City
     */
    private $city;

    /**
     * @var \Lists\DepartmentBundle\Entity\DepartmentsType
     */
    private $type;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $organization;

    /**
     * @var \Lists\DepartmentBundle\Entity\DepartmentsStatus
     */
    private $status;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $opermanager;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mpks = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Departments
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
     * Set fullname
     *
     * @param string $fullname
     *
     * @return Departments
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Departments
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set square
     *
     * @param float $square
     *
     * @return Departments
     */
    public function setSquare($square)
    {
        $this->square = $square;

        return $this;
    }

    /**
     * Get square
     *
     * @return float
     */
    public function getSquare()
    {
        return $this->square;
    }

    /**
     * Set isdeleted
     *
     * @param boolean $isdeleted
     *
     * @return Departments
     */
    public function setIsdeleted($isdeleted)
    {
        $this->isdeleted = $isdeleted;

        return $this;
    }

    /**
     * Get isdeleted
     *
     * @return boolean
     */
    public function getIsdeleted()
    {
        return $this->isdeleted;
    }

    /**
     * Set addedField
     *
     * @param string $addedField
     *
     * @return Departments
     */
    public function setAddedField($addedField)
    {
        $this->addedField = $addedField;

        return $this;
    }

    /**
     * Get addedField
     *
     * @return string
     */
    public function getAddedField()
    {
        return $this->addedField;
    }

    /**
     * Set statusDate
     *
     * @param \DateTime $statusDate
     *
     * @return Departments
     */
    public function setStatusDate($statusDate)
    {
        $this->statusDate = $statusDate;

        return $this;
    }

    /**
     * Get statusDate
     *
     * @return \DateTime
     */
    public function getStatusDate()
    {
        return $this->statusDate;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Departments
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
     * Set coordinates
     *
     * @param string $coordinates
     *
     * @return Departments
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    /**
     * Get coordinates
     *
     * @return string
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * Set organizationId
     *
     * @param integer $organizationId
     *
     * @return Departments
     */
    public function setOrganizationId($organizationId)
    {
        $this->organizationId = $organizationId;

        return $this;
    }

    /**
     * Get organizationId
     *
     * @return integer
     */
    public function getOrganizationId()
    {
        return $this->organizationId;
    }

    /**
     * Add mpks
     *
     * @param \Lists\MpkBundle\Entity\Mpk $mpks
     *
     * @return Departments
     */
    public function addMpk(\Lists\MpkBundle\Entity\Mpk $mpks)
    {
        $this->mpks[] = $mpks;

        return $this;
    }

    /**
     * Remove mpks
     *
     * @param \Lists\MpkBundle\Entity\Mpk $mpks
     */
    public function removeMpk(\Lists\MpkBundle\Entity\Mpk $mpks)
    {
        $this->mpks->removeElement($mpks);
    }

    /**
     * Get mpks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMpks()
    {
        return $this->mpks;
    }

    /**
     * Set city
     *
     * @param \Lists\CityBundle\Entity\City $city
     *
     * @return Departments
     */
    public function setCity(\Lists\CityBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \Lists\CityBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set type
     *
     * @param \Lists\DepartmentBundle\Entity\DepartmentsType $type
     *
     * @return Departments
     */
    public function setType(\Lists\DepartmentBundle\Entity\DepartmentsType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Lists\DepartmentBundle\Entity\DepartmentsType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set organization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organization
     *
     * @return Departments
     */
    public function setOrganization(\Lists\OrganizationBundle\Entity\Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return \Lists\OrganizationBundle\Entity\Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Set status
     *
     * @param \Lists\DepartmentBundle\Entity\DepartmentsStatus $status
     *
     * @return Departments
     */
    public function setStatus(\Lists\DepartmentBundle\Entity\DepartmentsStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \Lists\DepartmentBundle\Entity\DepartmentsStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set opermanager
     *
     * @param \SD\UserBundle\Entity\User $opermanager
     *
     * @return Departments
     */
    public function setOpermanager(\SD\UserBundle\Entity\User $opermanager = null)
    {
        $this->opermanager = $opermanager;

        return $this;
    }

    /**
     * Get opermanager
     *
     * @return \SD\UserBundle\Entity\User
     */
    public function getOpermanager()
    {
        return $this->opermanager;
    }

    /**
     * toString Method
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * toString Method
     *
     * @return string
     */
    public function getSelectLabel()
    {
        return $this->mpk.' | '.$this->city.' | '.$this->name;
    }

    /**
     * @var string
     */
    private $mpk;


    /**
     * Set mpk
     *
     * @param string $mpk
     *
     * @return Departments
     */
    public function setMpk($mpk)
    {
        $this->mpk = $mpk;

        return $this;
    }

    /**
     * Get mpk
     *
     * @return string 
     */
    public function getMpk()
    {
        return $this->mpk;
    }
    /**
     * onPrePersist
     */
    public function onPrePersist()
    {
        if ($this->isdeleted == null) {
            $this->isdeleted = false;
        }
        if (!empty($this->statusDate) && gettype($this->statusDate) == 'string') {
            $this->statusDate = new \DateTime($this->statusDate);
        }
    }
}
