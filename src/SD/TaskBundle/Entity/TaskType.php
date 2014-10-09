<?php

namespace SD\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SD\TaskBundle\Interfaces\Serializable;

/**
 * TaskType
 */
class TaskType implements Serializable
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
     * @return TaskType
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
     * sleep method
     *
     * @return array
     */
    public function customSerialize()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName()
        );
    }
}
