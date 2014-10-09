<?php

namespace SD\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SD\TaskBundle\Interfaces\Serializable;

/**
 * Stage
 */
class Stage implements Serializable
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
     * @var \SD\TaskBundle\Entity\Stage
     */
    private $parent;

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
     * @return Stage
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
     * @return Stage
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
     * Set parent
     *
     * @param \SD\TaskBundle\Entity\Stage $parent
     *
     * @return Stage
     */
    public function setParent (\SD\TaskBundle\Entity\Stage $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }
    /**
     * Get parent
     *
     * @return \SD\TaskBundle\Entity\Stage 
     */
    public function getParent ()
    {
        return $this->parent;
    }
    /**
     * @return string
     */
    public function __toString ()
    {

        return $this->getName();
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
