<?php

namespace Lists\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModelContact
 */
class ModelContact
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $modelName;

    /**
     * @var integer
     */
    private $modelId;

    /**
     * @var integer
     */
    private $sort;

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
    private $phone1;

    /**
     * @var string
     */
    private $phone2;

    /**
     * @var string
     */
    private $position;

    /**
     * @var \DateTime
     */
    private $birthday;

    /**
     * @var string
     */
    private $email;

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
     * Set id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set modelName
     *
     * @param string $modelName
     *
     * @return ModelContact
     */
    public function setModelName($modelName)
    {
        $this->modelName = $modelName;

        return $this;
    }

    /**
     * Get modelName
     *
     * @return string
     */
    public function getModelName()
    {
        return $this->modelName;
    }

    /**
     * Set modelId
     *
     * @param integer $modelId
     *
     * @return ModelContact
     */
    public function setModelId($modelId)
    {
        $this->modelId = $modelId;

        return $this;
    }

    /**
     * Get modelId
     *
     * @return integer
     */
    public function getModelId()
    {
        return $this->modelId;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     *
     * @return ModelContact
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return ModelContact
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
     * @return ModelContact
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
     * @return ModelContact
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
     * Set phone1
     *
     * @param string $phone1
     *
     * @return ModelContact
     */
    public function setPhone1($phone1)
    {
        $this->phone1 = $phone1;

        return $this;
    }

    /**
     * Get phone1
     *
     * @return string
     */
    public function getPhone1()
    {
        return $this->phone1;
    }

    /**
     * Set phone2
     *
     * @param string $phone2
     *
     * @return ModelContact
     */
    public function setPhone2($phone2)
    {
        $this->phone2 = $phone2;

        return $this;
    }

    /**
     * Get phone2
     *
     * @return string
     */
    public function getPhone2()
    {
        return $this->phone2;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return ModelContact
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
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return ModelContact
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
     * Set email
     *
     * @param string $email
     *
     * @return ModelContact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @var \DateTime
     */
    private $createdatetime;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $user;

    /**
     * Set createdatetime
     *
     * @param \DateTime $createdatetime
     *
     * @return ModelContact
     */
    public function setCreatedatetime($createdatetime)
    {
        $this->createdatetime = $createdatetime;

        return $this;
    }

    /**
     * Get createdatetime
     *
     * @return \DateTime
     */
    public function getCreatedatetime()
    {
        return $this->createdatetime;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return ModelContact
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
     * @var \SD\UserBundle\Entity\User
     */
    private $owner;

    /**
     * Set owner
     *
     * @param \SD\UserBundle\Entity\User $owner
     *
     * @return ModelContact
     */
    public function setOwner(\SD\UserBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        $this->setOwnerdatetime(new \DateTime());

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
     * @ORM\PrePersist
     */
    public function doOnPrePersist()
    {
        if (!$this->getId()) {
            $this->setCreatedatetime(new \DateTime());
        }
    }

    /**
     * @var \DateTime
     */
    private $ownerdatetime;

    /**
     * Set ownerdatetime
     *
     * @param \DateTime $ownerdatetime
     *
     * @return ModelContact
     */
    public function setOwnerdatetime($ownerdatetime)
    {
        $this->ownerdatetime = $ownerdatetime;

        return $this;
    }

    /**
     * Get ownerdatetime
     *
     * @return \DateTime
     */
    public function getOwnerdatetime()
    {
        return $this->ownerdatetime;
    }

    /**
     * @var integer
     */
    private $owner_id;

    /**
     * Set owner_id
     *
     * @param integer $ownerId
     *
     * @return ModelContact
     */
    public function setOwnerId($ownerId)
    {
        // @codingStandardsIgnoreStart
        $this->owner_id = $ownerId;
        // @codingStandardsIgnoreEnd
        $this->setOwnerdatetime(new \DateTime());

        return $this;
    }

    /**
     * Get owner_id
     *
     * @return integer
     */
    public function getOwnerId()
    {
        // @codingStandardsIgnoreStart
        return $this->owner_id;
        // @codingStandardsIgnoreEnd
    }

    /**
     * @var \Lists\ContactBundle\Entity\ModelContactType
     */
    private $type;

    /**
     * Set type
     *
     * @param \Lists\ContactBundle\Entity\ModelContactType $type
     *
     * @return ModelContact
     */
    public function setType(\Lists\ContactBundle\Entity\ModelContactType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Lists\ContactBundle\Entity\ModelContactType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->getLastName() . ' ' . $this->getFirstName();
    }

    /**
     * __toString()
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getFullName() . ' ' . $this->getPhone1();
    }

    /**
     * @var integer
     */
    private $type_id;

    /**
     * Set type_id
     *
     * @param integer $typeId
     *
     * @return ModelContact
     */
    public function setTypeId($typeId)
    {
        // @codingStandardsIgnoreStart
        $this->type_id = $typeId;
        // @codingStandardsIgnoreEnd
        return $this;
    }

    /**
     * Get type_id
     *
     * @return integer
     */
    public function getTypeId()
    {
        // @codingStandardsIgnoreStart
        return $this->type_id;
        // @codingStandardsIgnoreEnd
    }

    /**
     * Sets birthday from birthdayString
     *
     * @param string $birthdayString
     */
    public function setBirthdayString($birthdayString)
    {
        if ($birthdayString) {
            $this->setBirthday(new \DateTime($birthdayString));
        }
    }

    /**
     * @var boolean
     */
    private $isShared;

    /**
     * Set isShared
     *
     * @param boolean $isShared
     *
     * @return ModelContact
     */
    public function setIsShared($isShared)
    {
        $this->isShared = $isShared;

        return $this;
    }

    /**
     * Get isShared
     *
     * @return boolean
     */
    public function getIsShared()
    {
        return $this->isShared;
    }
    /**
     * @var integer
     */
    private $levelId;

    /**
     * @var \Lists\ContactBundle\Entity\ModelContactLevel
     */
    private $level;


    /**
     * Set levelId
     *
     * @param integer $levelId
     *
     * @return ModelContact
     */
    public function setLevelId($levelId)
    {
        $this->levelId = $levelId;

        return $this;
    }

    /**
     * Get levelId
     *
     * @return integer 
     */
    public function getLevelId()
    {
        return $this->levelId;
    }

    /**
     * Set level
     *
     * @param \Lists\ContactBundle\Entity\ModelContactLevel $level
     *
     * @return ModelContact
     */
    public function setLevel(\Lists\ContactBundle\Entity\ModelContactLevel $level = null)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return \Lists\ContactBundle\Entity\ModelContactLevel 
     */
    public function getLevel()
    {
        return $this->level;
    }
    /**
     * @var \Lists\ContactBundle\Entity\ModelContactSendEmail
     */
    private $sendEmail;


    /**
     * Set sendEmail
     *
     * @param \Lists\ContactBundle\Entity\ModelContactSendEmail $sendEmail
     * 
     * @return ModelContact
     */
    public function setSendEmail(\Lists\ContactBundle\Entity\ModelContactSendEmail $sendEmail = null)
    {
        $this->sendEmail = $sendEmail;

        return $this;
    }

    /**
     * Get sendEmail
     *
     * @return \Lists\ContactBundle\Entity\ModelContactSendEmail 
     */
    public function getSendEmail()
    {
        return $this->sendEmail;
    }
}