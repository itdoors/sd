<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectFileType
 */
class ProjectFileType
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
    private $group;

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
     * @return ProjectFileType
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
     * Set group
     *
     * @param string $group
     *
     * @return ProjectFileType
     */
    public function setGroup ($group)
    {
        $this->group = $group;

        return $this;
    }
    /**
     * Get group
     *
     * @return string 
     */
    public function getGroup ()
    {
        return $this->group;
    }
    /**
     * @var string
     */
    private $alias;


    /**
     * Set alias
     *
     * @param string $alias
     * @return ProjectFileType
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }
}
