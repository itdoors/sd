<?php

namespace Lists\IndividualBundle\Entity;

/**
 * Individual
 */
class Individual
{
    /**
     * @var integer
     */
    private $id;

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
     * @var \DateTime
     */
    private $birthday;

    /**
     * @var string
     */
    private $tin;

    /**
     * @var string
     */
    private $passport;

    /**
     * @var string
     */
    private $address;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $actions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->actions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->businessRoles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get actions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActions()
    {
        return $this->actions;
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Individual
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
     * @return Individual
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
     * @return Individual
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
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Individual
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
     * Set tin
     *
     * @param string $tin
     *
     * @return Individual
     */
    public function setTin($tin)
    {
        $this->tin = $tin;

        return $this;
    }

    /**
     * Get tin
     *
     * @return string
     */
    public function getTin()
    {
        return $this->tin;
    }

    /**
     * Set passport
     *
     * @param string $passport
     *
     * @return Individual
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
     * Set address
     *
     * @param string $address
     *
     * @return Individual
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
     * @var string
     */
    private $phone;

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Individual
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
     * to String Method
     *
     * @return string
     */
    public function __toString()
    {
        return $this->lastName.' '.$this->firstName;
    }

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $businessRoles;

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * 
     * @return Individual
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

    /**
     * Add businessRole
     *
     * @param \SD\BusinessRoleBundle\Entity\BusinessRole $businessRole
     * 
     * @return Individual
     */
    public function addBusinessRole(\SD\BusinessRoleBundle\Entity\BusinessRole $businessRole)
    {
        $this->businessRoles[] = $businessRole;

        return $this;
    }

    /**
     * Remove businessRoles
     *
     * @param \SD\BusinessRoleBundle\Entity\BusinessRole $businessRole
     */
    public function removeBusinessRole(\SD\BusinessRoleBundle\Entity\BusinessRole $businessRole)
    {
        $this->businessRoles->removeElement($businessRole);
    }

    /**
     * Get businessRoles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBusinessRoles()
    {
        return $this->businessRoles;
    }

    /**
     * Add actions
     *
     * @param \Lists\CoachBundle\Entity\Action $actions
     * 
     * @return Individual
     */
    public function addAction(\Lists\CoachBundle\Entity\Action $actions)
    {
        $this->actions[] = $actions;

        return $this;
    }

    /**
     * Remove actions
     *
     * @param \Lists\CoachBundle\Entity\Action $actions
     */
    public function removeAction(\Lists\CoachBundle\Entity\Action $actions)
    {
        $this->actions->removeElement($actions);
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $contacts;

    /**
     * Add contact
     *
     * @param \Lists\IndividualBundle\Entity\Contact $contact
     * 
     * @return Individual
     */
    public function addContact(\Lists\IndividualBundle\Entity\Contact $contact)
    {
        $contact->setIndividual($this);
        $this->contacts[] = $contact;

        return $this;
    }

    /**
     * Remove contact
     *
     * @param \Lists\IndividualBundle\Entity\Contact $contact
     */
    public function removeContact(\Lists\IndividualBundle\Entity\Contact $contact)
    {
        $this->contacts->removeElement($contact);
    }

    /**
     * Get contacts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContacts()
    {
        return $this->contacts;
    }
}
