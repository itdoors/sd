<?php

namespace Lists\HandlingBundle\Entity;

/**
 * HandlingService
 */
class HandlingService
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
     * @var integer
     */
    protected $sortorder;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $handlings;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->handlings = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return HandlingService
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
     * Set slug
     *
     * @param string $slug
     *
     * @return HandlingService
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set sortorder
     *
     * @param integer $sortorder
     *
     * @return HandlingService
     */
    public function setSortorder($sortorder)
    {
        $this->sortorder = $sortorder;

        return $this;
    }

    /**
     * Get sortorder
     *
     * @return integer
     */
    public function getSortorder()
    {
        return $this->sortorder;
    }

    /**
     * Add handlings
     *
     * @param \Lists\HandlingBundle\Entity\Handling $handlings
     *
     * @return HandlingService
     */
    public function addHandling(\Lists\HandlingBundle\Entity\Handling $handlings)
    {
        $this->handlings[] = $handlings;

        return $this;
    }

    /**
     * Remove handlings
     *
     * @param \Lists\HandlingBundle\Entity\Handling $handlings
     */
    public function removeHandling(\Lists\HandlingBundle\Entity\Handling $handlings)
    {
        $this->handlings->removeElement($handlings);
    }

    /**
     * Get handlings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHandlings()
    {
        return $this->handlings;
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
     * @return array
     */
    public function __sleep()
    {
        return array(
            'id',
        );
    }
}