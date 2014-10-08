<?php

namespace Lists\HandlingBundle\Entity;

/**
 * HandlingResult
 */
class HandlingResult
{
    const RESULT_CLOSED = 'closed';
    const RESULT_COMPETITOR = 'competitor';

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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return HandlingResult
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
     * @return HandlingResult
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
     * __toStrong
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $handlings;

    /**
     * Add handlings
     *
     * @param \Lists\HandlingBundle\Entity\Handling $handlings
     *
     * @return HandlingResult
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
     * Constructor
     */
    public function __construct()
    {
        $this->handlings = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return HandlingResult
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
     * @return array
     */
    public function __sleep()
    {
        return array(
            'id',
            'name',
        );
    }

    /**
     * @var string
     */
    private $percentageString;

    /**
     * @var integer
     */
    private $progress;

    /**
     * Set percentageString
     *
     * @param string $percentageString
     *
     * @return HandlingResult
     */
    public function setPercentageString($percentageString)
    {
        $this->percentageString = $percentageString;

        return $this;
    }

    /**
     * Get percentageString
     *
     * @return string
     */
    public function getPercentageString()
    {
        return $this->percentageString;
    }

    /**
     * Set progress
     *
     * @param integer $progress
     *
     * @return HandlingResult
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * Get progress
     *
     * @return integer
     */
    public function getProgress()
    {
        return $this->progress;
    }
}
