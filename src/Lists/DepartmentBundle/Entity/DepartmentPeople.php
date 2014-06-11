<?php

namespace Lists\DepartmentBundle\Entity;

/**
 * DepartmentPeople
 */
class DepartmentPeople
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
    private $number;

    /**
     * @var string
     */
    private $positionString;

    /**
     * @var string
     */
    private $typeString;

    /**
     * @var string
     */
    private $contacts;

    /**
     * @var integer
     */
    private $year;

    /**
     * @var integer
     */
    private $month;

    /**
     * @var string
     */
    private $salary;

    /**
     * @var \DateTime
     */
    private $birthday;

    /**
     * @var integer
     */
    private $employmentTypeId;

    /**
     * @var float
     */
    private $bonus;

    /**
     * @var float
     */
    private $fine;

    /**
     * @var boolean
     */
    private $isCleanSalary;

    /**
     * @var integer
     */
    private $normaDays;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $middleName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var boolean
     */
    private $isFromOneC;

    /**
     * @var boolean
     */
    private $isApproved;

    /**
     * @var string
     */
    private $drfo;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $address;

    /**
     * @var \DateTime
     */
    private $admissionDate;

    /**
     * @var \DateTime
     */
    private $dismissalDate;

    /**
     * @var string
     */
    private $personCode;

    /**
     * @var \DateTime
     */
    private $admissionDateNotOfficially;

    /**
     * @var \DateTime
     */
    private $dismissalDateNotOfficially;

    /**
     * @var string
     */
    private $passport;

    /**
     * @var \Lists\IndividualBundle\Entity\Individual
     */
    private $individual;

    /**
     * @var \Lists\DepartmentBundle\Entity\Departments
     */
    private $department;

    /**
     * @var \Lists\DepartmentBundle\Entity\DepartmentPeople
     */
    private $parent;

    /**
     * @var \Lists\LookupBundle\Entity\Lookup
     */
    private $salaryType;

    /**
     * @var \Lists\LookupBundle\Entity\Lookup
     */
    private $type;

    /**
     * @var \Lists\DepartmentBundle\Entity\DepartmentPeoplePosition
     */
    private $position;

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
     * @return DepartmentPeople
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
     * Set number
     *
     * @param string $number
     *
     * @return DepartmentPeople
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set positionString
     *
     * @param string $positionString
     * 
     * @return DepartmentPeople
     */
    public function setPositionString($positionString)
    {
        $this->positionString = $positionString;

        return $this;
    }

    /**
     * Get positionString
     *
     * @return string
     */
    public function getPositionString()
    {
        return $this->positionString;
    }

    /**
     * Set typeString
     *
     * @param string $typeString
     *
     * @return DepartmentPeople
     */
    public function setTypeString($typeString)
    {
        $this->typeString = $typeString;

        return $this;
    }

    /**
     * Get typeString
     *
     * @return string
     */
    public function getTypeString()
    {
        return $this->typeString;
    }

    /**
     * Set contacts
     *
     * @param string $contacts
     *
     * @return DepartmentPeople
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * Get contacts
     *
     * @return string
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return DepartmentPeople
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set month
     *
     * @param integer $month
     *
     * @return DepartmentPeople
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set salary
     *
     * @param string $salary
     *
     * @return DepartmentPeople
     */
    public function setSalary($salary)
    {
        $this->salary = $salary;

        return $this;
    }

    /**
     * Get salary
     *
     * @return string
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return DepartmentPeople
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
     * Set employmentTypeId
     *
     * @param integer $employmentTypeId
     *
     * @return DepartmentPeople
     */
    public function setEmploymentTypeId($employmentTypeId)
    {
        $this->employmentTypeId = $employmentTypeId;

        return $this;
    }

    /**
     * Get employmentTypeId
     *
     * @return integer
     */
    public function getEmploymentTypeId()
    {
        return $this->employmentTypeId;
    }

    /**
     * Set bonus
     *
     * @param float $bonus
     *
     * @return DepartmentPeople
     */
    public function setBonus($bonus)
    {
        $this->bonus = $bonus;

        return $this;
    }

    /**
     * Get bonus
     *
     * @return float
     */
    public function getBonus()
    {
        return $this->bonus;
    }

    /**
     * Set fine
     *
     * @param float $fine
     *
     * @return DepartmentPeople
     */
    public function setFine($fine)
    {
        $this->fine = $fine;

        return $this;
    }

    /**
     * Get fine
     *
     * @return float
     */
    public function getFine()
    {
        return $this->fine;
    }

    /**
     * Set isCleanSalary
     *
     * @param boolean $isCleanSalary
     *
     * @return DepartmentPeople
     */
    public function setIsCleanSalary($isCleanSalary)
    {
        $this->isCleanSalary = $isCleanSalary;

        return $this;
    }

    /**
     * Get isCleanSalary
     *
     * @return boolean
     */
    public function getIsCleanSalary()
    {
        return $this->isCleanSalary;
    }

    /**
     * Set normaDays
     *
     * @param integer $normaDays
     *
     * @return DepartmentPeople
     */
    public function setNormaDays($normaDays)
    {
        $this->normaDays = $normaDays;

        return $this;
    }

    /**
     * Get normaDays
     *
     * @return integer
     */
    public function getNormaDays()
    {
        return $this->normaDays;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return DepartmentPeople
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
     * Set middleName
     *
     * @param string $middleName
     *
     * @return DepartmentPeople
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
     * Set lastName
     *
     * @param string $lastName
     *
     * @return DepartmentPeople
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
     * Set isFromOneC
     *
     * @param boolean $isFromOneC
     *
     * @return DepartmentPeople
     */
    public function setIsFromOneC($isFromOneC)
    {
        $this->isFromOneC = $isFromOneC;

        return $this;
    }

    /**
     * Get isFromOneC
     *
     * @return boolean
     */
    public function getIsFromOneC()
    {
        return $this->isFromOneC;
    }

    /**
     * Set isApproved
     *
     * @param boolean $isApproved
     *
     * @return DepartmentPeople
     */
    public function setIsApproved($isApproved)
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    /**
     * Get isApproved
     *
     * @return boolean
     */
    public function getIsApproved()
    {
        return $this->isApproved;
    }

    /**
     * Set drfo
     *
     * @param string $drfo
     *
     * @return DepartmentPeople
     */
    public function setDrfo($drfo)
    {
        $this->drfo = $drfo;

        return $this;
    }

    /**
     * Get drfo
     *
     * @return string
     */
    public function getDrfo()
    {
        return $this->drfo;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return DepartmentPeople
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return DepartmentPeople
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
     * Set admissionDate
     *
     * @param \DateTime $admissionDate
     *
     * @return DepartmentPeople
     */
    public function setAdmissionDate($admissionDate)
    {
        $this->admissionDate = $admissionDate;

        return $this;
    }

    /**
     * Get admissionDate
     *
     * @return \DateTime
     */
    public function getAdmissionDate()
    {
        return $this->admissionDate;
    }

    /**
     * Set dismissalDate
     *
     * @param \DateTime $dismissalDate
     *
     * @return DepartmentPeople
     */
    public function setDismissalDate($dismissalDate)
    {
        $this->dismissalDate = $dismissalDate;

        return $this;
    }

    /**
     * Get dismissalDate
     *
     * @return \DateTime
     */
    public function getDismissalDate()
    {
        return $this->dismissalDate;
    }

    /**
     * Set personCode
     *
     * @param string $personCode
     *
     * @return DepartmentPeople
     */
    public function setPersonCode($personCode)
    {
        $this->personCode = $personCode;

        return $this;
    }

    /**
     * Get personCode
     *
     * @return string
     */
    public function getPersonCode()
    {
        return $this->personCode;
    }

    /**
     * Set admissionDateNotOfficially
     *
     * @param \DateTime $admissionDateNotOfficially
     *
     * @return DepartmentPeople
     */
    public function setAdmissionDateNotOfficially($admissionDateNotOfficially)
    {
        $this->admissionDateNotOfficially = $admissionDateNotOfficially;

        return $this;
    }

    /**
     * Get admissionDateNotOfficially
     *
     * @return \DateTime
     */
    public function getAdmissionDateNotOfficially()
    {
        return $this->admissionDateNotOfficially;
    }

    /**
     * Set dismissalDateNotOfficially
     *
     * @param \DateTime $dismissalDateNotOfficially
     *
     * @return DepartmentPeople
     */
    public function setDismissalDateNotOfficially($dismissalDateNotOfficially)
    {
        $this->dismissalDateNotOfficially = $dismissalDateNotOfficially;

        return $this;
    }

    /**
     * Get dismissalDateNotOfficially
     *
     * @return \DateTime
     */
    public function getDismissalDateNotOfficially()
    {
        return $this->dismissalDateNotOfficially;
    }

    /**
     * Set passport
     *
     * @param string $passport
     *
     * @return DepartmentPeople
     */
    public function setPassport($passport)
    {
        $this->passport = $passport;

        return $this;
    }

    /**
     * Get passport
     *
     * @return string
     */
    public function getPassport()
    {
        return $this->passport;
    }

    /**
     * Set individual
     *
     * @param \Lists\IndividualBundle\Entity\Individual $individual
     *
     * @return DepartmentPeople
     */
    public function setIndividual(\Lists\IndividualBundle\Entity\Individual $individual = null)
    {
        $this->individual = $individual;

        return $this;
    }

    /**
     * Get individual
     *
     * @return \Lists\IndividualBundle\Entity\Individual
     */
    public function getIndividual()
    {
        return $this->individual;
    }

    /**
     * Set department
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $department
     *
     * @return DepartmentPeople
     */
    public function setDepartment(\Lists\DepartmentBundle\Entity\Departments $department = null)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return \Lists\DepartmentBundle\Entity\Departments
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set parent
     *
     * @param \Lists\DepartmentBundle\Entity\DepartmentPeople $parent
     *
     * @return DepartmentPeople
     */
    public function setParent(\Lists\DepartmentBundle\Entity\DepartmentPeople $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Lists\DepartmentBundle\Entity\DepartmentPeople
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set salaryType
     *
     * @param \Lists\LookupBundle\Entity\Lookup $salaryType
     *
     * @return DepartmentPeople
     */
    public function setSalaryType(\Lists\LookupBundle\Entity\Lookup $salaryType = null)
    {
        $this->salaryType = $salaryType;

        return $this;
    }

    /**
     * Get salaryType
     *
     * @return \Lists\LookupBundle\Entity\Lookup
     */
    public function getSalaryType()
    {
        return $this->salaryType;
    }

    /**
     * Set type
     *
     * @param \Lists\LookupBundle\Entity\Lookup $type
     *
     * @return DepartmentPeople
     */
    public function setType(\Lists\LookupBundle\Entity\Lookup $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Lists\LookupBundle\Entity\Lookup
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set position
     *
     * @param \Lists\DepartmentBundle\Entity\DepartmentPeoplePosition $position
     *
     * @return DepartmentPeople
     */
    public function setPosition(\Lists\DepartmentBundle\Entity\DepartmentPeoplePosition $position = null)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return \Lists\DepartmentBundle\Entity\DepartmentPeoplePosition
     */
    public function getPosition()
    {
        return $this->position;
    }
    /**
     * @var \Lists\MpkBundle\Entity\Mpk
     */
    private $mpks;

    /**
     * Set mpks
     *
     * @param \Lists\MpkBundle\Entity\Mpk $mpks
     *
     * @return DepartmentPeople
     */
    public function setMpks(\Lists\MpkBundle\Entity\Mpk $mpks = null)
    {
        $this->mpks = $mpks;

        return $this;
    }

    /**
     * Get mpks
     *
     * @return \Lists\MpkBundle\Entity\Mpk
     */
    public function getMpks()
    {
        return $this->mpks;
    }
}
