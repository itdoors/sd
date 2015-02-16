<?php

namespace SD\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskPattern
 */
class TaskPattern
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
    private $title;

    /**
     * @var string
     */
    private $description;


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
     * @return TaskPattern
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
     * Set title
     *
     * @param string $title
     *
     * @return TaskPattern
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return TaskPattern
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
     * @return string
     */
    public function __toString()
    {

        return $this->getName();
    }
    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $responsible;


    /**
     * Set responsible
     *
     * @param \SD\UserBundle\Entity\User $responsible
     * @return TaskPattern
     */
    public function setResponsible(\SD\UserBundle\Entity\User $responsible = null)
    {
        $this->responsible = $responsible;
    
        return $this;
    }

    /**
     * Get responsible
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getResponsible()
    {
        return $this->responsible;
    }
}