<?php

namespace Lists\CoachBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Action
 */
class Action
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
     * @var \DateTime
     */
    private $startedAt;

    /**
     * @var \DateTime
     */
    private $finishedAt;

    /**
     * @var \Lists\CoachBundle\Entity\CoachReport
     */
    private $coachReport;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $executor;

    /**
     * @var \Lists\CoachBundle\Entity\ActionTopic
     */
    private $topic;

    /**
     * @var \Lists\CoachBundle\Entity\ActionType
     */
    private $type;

    /**
     * @var \Lists\DepartmentBundle\Entity\Departments
     */
    private $department;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $individuals;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->individuals = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set text
     *
     * @param string $text
     * 
     * @return Action
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set startedAt
     *
     * @param \DateTime $startedAt
     * 
     * @return Action
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    /**
     * Get startedAt
     *
     * @return \DateTime 
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * Set finishedAt
     *
     * @param \DateTime $finishedAt
     * 
     * @return Action
     */
    public function setFinishedAt($finishedAt)
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    /**
     * Get finishedAt
     *
     * @return \DateTime 
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * Set coachReport
     *
     * @param \Lists\CoachBundle\Entity\CoachReport $coachReport
     * 
     * @return Action
     */
    public function setCoachReport(\Lists\CoachBundle\Entity\CoachReport $coachReport = null)
    {
        $this->coachReport = $coachReport;

        return $this;
    }

    /**
     * Get coachReport
     *
     * @return \Lists\CoachBundle\Entity\CoachReport 
     */
    public function getCoachReport()
    {
        return $this->coachReport;
    }

    /**
     * Set executor
     *
     * @param \SD\UserBundle\Entity\User $executor
     * 
     * @return Action
     */
    public function setExecutor(\SD\UserBundle\Entity\User $executor = null)
    {
        $this->executor = $executor;

        return $this;
    }

    /**
     * Get executor
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getExecutor()
    {
        return $this->executor;
    }

    /**
     * Set topic
     *
     * @param \Lists\CoachBundle\Entity\ActionTopic $topic
     * 
     * @return Action
     */
    public function setTopic(\Lists\CoachBundle\Entity\ActionTopic $topic = null)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic
     *
     * @return \Lists\CoachBundle\Entity\ActionTopic 
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set type
     *
     * @param \Lists\CoachBundle\Entity\ActionType $type
     * 
     * @return Action
     */
    public function setType(\Lists\CoachBundle\Entity\ActionType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Lists\CoachBundle\Entity\ActionType 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set department
     *
     * @param \Lists\DepartmentBundle\Entity\Departments $department
     * 
     * @return Action
     */
    public function setDepartment(\Lists\DepartmentBundle\Entity\Departments $department = null)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return \Lists\DepartmentBundle\Entity\Departments 
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Add individuals
     *
     * @param \Lists\IndividualBundle\Entity\Individual $individuals
     * 
     * @return Action
     */
    public function addIndividual(\Lists\IndividualBundle\Entity\Individual $individuals)
    {
        $this->individuals[] = $individuals;

        return $this;
    }

    /**
     * Remove individuals
     *
     * @param \Lists\IndividualBundle\Entity\Individual $individuals
     */
    public function removeIndividual(\Lists\IndividualBundle\Entity\Individual $individuals)
    {
        $this->individuals->removeElement($individuals);
    }

    /**
     * Get individuals
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIndividuals()
    {
        return $this->individuals;
    }

    /**
     * Set individuals
     *
     * @param \Doctrine\Common\Collections\Collection $individuals
     * 
     * @return Action
     */
    public function setIndividuals(\Doctrine\Common\Collections\Collection $individuals)
    {
        $this->individuals = $individuals;

        return $this;
    }
}
