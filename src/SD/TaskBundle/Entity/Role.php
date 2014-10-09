<?php

namespace SD\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SD\TaskBundle\Interfaces\Serializable;

/**
 * Role
 */
class Role implements Serializable
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
    private $model;

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
     * Set name
     *
     * @param string $name
     *
     * @return Role
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
     * Set model
     *
     * @param string $model
     *
     * @return Role
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
     * @return string
     */
    public function __toString ()
    {

        return $this->name;
    }

    /**
     * sleep method
     *
     * @return array
     */
    public function customSerialize()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'model' => $this->getModel()
        );
    }
}
