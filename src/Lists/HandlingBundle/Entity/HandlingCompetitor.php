<?php

namespace Lists\HandlingBundle\Entity;

/**
 * HandlingCompetitor
 */
class HandlingCompetitor
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $handlingId;

    /**
     * @var integer
     */
    private $competitorId;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var string
     */
    private $strengths;

    /**
     * @var string
     */
    private $weaknesses;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $competitor;

    /**
     * @var \Lists\HandlingBundle\Entity\Handling
     */
    private $handling;

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
     * Set handlingId
     *
     * @param integer $handlingId
     *
     * @return HandlingCompetitor
     */
    public function setHandlingId($handlingId)
    {
        $this->handlingId = $handlingId;

        return $this;
    }

    /**
     * Get handlingId
     *
     * @return integer
     */
    public function getHandlingId()
    {
        return $this->handlingId;
    }

    /**
     * Set competitorId
     *
     * @param integer $competitorId
     *
     * @return HandlingCompetitor
     */
    public function setCompetitorId($competitorId)
    {
        $this->competitorId = $competitorId;

        return $this;
    }

    /**
     * Get competitorId
     *
     * @return integer
     */
    public function getCompetitorId()
    {
        return $this->competitorId;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return HandlingCompetitor
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set strengths
     *
     * @param string $strengths
     *
     * @return HandlingCompetitor
     */
    public function setStrengths($strengths)
    {
        $this->strengths = $strengths;

        return $this;
    }

    /**
     * Get strengths
     *
     * @return string
     */
    public function getStrengths()
    {
        return $this->strengths;
    }

    /**
     * Set weaknesses
     *
     * @param string $weaknesses
     *
     * @return HandlingCompetitor
     */
    public function setWeaknesses($weaknesses)
    {
        $this->weaknesses = $weaknesses;

        return $this;
    }

    /**
     * Get weaknesses
     *
     * @return string
     */
    public function getWeaknesses()
    {
        return $this->weaknesses;
    }

    /**
     * Set competitor
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $competitor
     *
     * @return HandlingCompetitor
     */
    public function setCompetitor(\Lists\OrganizationBundle\Entity\Organization $competitor = null)
    {
        $this->competitor = $competitor;

        return $this;
    }

    /**
     * Get competitor
     *
     * @return \Lists\OrganizationBundle\Entity\Organization
     */
    public function getCompetitor()
    {
        return $this->competitor;
    }

    /**
     * Set handling
     *
     * @param \Lists\HandlingBundle\Entity\Handling $handling
     *
     * @return HandlingCompetitor
     */
    public function setHandling(\Lists\HandlingBundle\Entity\Handling $handling = null)
    {
        $this->handling = $handling;

        return $this;
    }

    /**
     * Get handling
     *
     * @return \Lists\HandlingBundle\Entity\Handling
     */
    public function getHandling()
    {
        return $this->handling;
    }
}
