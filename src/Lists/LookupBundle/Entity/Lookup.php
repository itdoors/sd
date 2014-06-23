<?php

namespace Lists\LookupBundle\Entity;

/**
 * Lookup
 */
class Lookup
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
    private $lukey;

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
     * @return Lookup
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
     * Set lukey
     *
     * @param string $lukey
     *
     * @return Lookup
     */
    public function setLukey($lukey)
    {
        $this->lukey = $lukey;

        return $this;
    }

    /**
     * Get lukey
     *
     * @return string
     */
    public function getLukey()
    {
        return $this->lukey;
    }

    /**
     * __toStrong
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
    /**
     * @var string
     */
    private $group;


    /**
     * Set group
     *
     * @param string $group
     * 
     * @return Lookup
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return string 
     */
    public function getGroup()
    {
        return $this->group;
    }
}
