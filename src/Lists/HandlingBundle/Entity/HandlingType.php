<?php

namespace Lists\HandlingBundle\Entity;

/**
 * HandlingType
 */
class HandlingType
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $slug;

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
     * @return HandlingType
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
     * Set slug
     *
     * @param string $slug
     *
     * @return HandlingType
     */
    public function setSlug ($slug)
    {
        $this->slug = $slug;

        return $this;
    }
    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug ()
    {
        return $this->slug;
    }
    /**
     * __toStrong
     *
     * @return string
     */
    public function __toString ()
    {
        return $this->getName();
    }

    /**
     * @var integer
     */
    protected $sortorder;

    /**
     * Set sortorder
     *
     * @param integer $sortorder
     *
     * @return HandlingType
     */
    public function setSortorder ($sortorder)
    {
        $this->sortorder = $sortorder;

        return $this;
    }
    /**
     * Get sortorder
     *
     * @return integer
     */
    public function getSortorder ()
    {
        return $this->sortorder;
    }
    /**
     * @return array
     */
    public function __sleep ()
    {
        return array (
            'id',
        );
    }

    /**
     * @var string
     */
    private $alias;

    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return HandlingType
     */
    public function setAlias ($alias)
    {
        $this->alias = $alias;

        return $this;
    }
    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias ()
    {
        return $this->alias;
    }
}
