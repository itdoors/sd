<?php

namespace SD\CalendarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vote
 */
class Vote
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $userId;

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
    private $value;

    /**
     * @var \DateTime
     */
    private $dateCreate;

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
     * Set userId
     *
     * @param integer $userId
     * 
     * @return Vote
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set modelName
     *
     * @param string $modelName
     * 
     * @return Vote
     */
    public function setModelName($modelName)
    {
        $this->modelName = $modelName;

        return $this;
    }

    /**
     * Get model
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
     * @return Vote
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
     * Set value
     *
     * @param integer $value
     * 
     * @return Vote
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * 
     * @return Vote
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime 
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }
    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $user;


    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * 
     * @return Vote
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
