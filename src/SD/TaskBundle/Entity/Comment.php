<?php

namespace SD\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 */
class Comment
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createDatetime;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $model;

    /**
     * @var integer
     */
    private $modelId;

    /**
     * @var string
     */
    private $additionField;

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
     * Set createDatetime
     *
     * @param \DateTime $createDatetime
     *
     * @return Comment
     */
    public function setCreateDatetime ($createDatetime)
    {
        $this->createDatetime = $createDatetime;

        return $this;
    }
    /**
     * Get createDatetime
     *
     * @return \DateTime 
     */
    public function getCreateDatetime ()
    {
        return $this->createDatetime;
    }
    /**
     * Set value
     *
     * @param string $value
     *
     * @return Comment
     */
    public function setValue ($value)
    {
        $this->value = $value;

        return $this;
    }
    /**
     * Get value
     *
     * @return string 
     */
    public function getValue ()
    {
        return $this->value;
    }
    /**
     * Set model
     *
     * @param string $model
     *
     * @return Comment
     */
    public function setModel ($model)
    {
        $this->model = $model;

        return $this;
    }
    /**
     * Get model
     *
     * @return string 
     */
    public function getModel ()
    {
        return $this->model;
    }
    /**
     * Set modelId
     *
     * @param integer $modelId
     *
     * @return Comment
     */
    public function setModelId ($modelId)
    {
        $this->modelId = $modelId;

        return $this;
    }
    /**
     * Get modelId
     *
     * @return integer 
     */
    public function getModelId ()
    {
        return $this->modelId;
    }
    /**
     * Set additionField
     *
     * @param string $additionField
     *
     * @return Comment
     */
    public function setAdditionField ($additionField)
    {
        $this->additionField = $additionField;

        return $this;
    }
    /**
     * Get additionField
     *
     * @return string 
     */
    public function getAdditionField ()
    {
        return $this->additionField;
    }
    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return Comment
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
}
