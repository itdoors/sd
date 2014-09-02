<?php

namespace SD\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskEndDate
 */
class TaskEndDate
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $changeDateTime;

    /**
     * @var \DateTime
     */
    private $endDateTime;

    /**
     * @var \SD\TaskBundle\Entity\Task
     */
    private $task;


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
     * Set changeDateTime
     *
     * @param \DateTime $changeDateTime
     *
     * @return TaskEndDate
     */
    public function setChangeDateTime($changeDateTime)
    {
        $this->changeDateTime = $changeDateTime;

        return $this;
    }

    /**
     * Get changeDateTime
     *
     * @return \DateTime 
     */
    public function getChangeDateTime()
    {
        return $this->changeDateTime;
    }

    /**
     * Set endDateTime
     *
     * @param \DateTime $endDateTime
     *
     * @return TaskEndDate
     */
    public function setEndDateTime($endDateTime)
    {
        $this->endDateTime = $endDateTime;

        return $this;
    }

    /**
     * Get endDateTime
     *
     * @return \DateTime 
     */
    public function getEndDateTime()
    {
        return $this->endDateTime;
    }

    /**
     * Set task
     *
     * @param \SD\TaskBundle\Entity\Task $task
     *
     * @return TaskEndDate
     */
    public function setTask(\SD\TaskBundle\Entity\Task $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return \SD\TaskBundle\Entity\Task 
     */
    public function getTask()
    {
        return $this->task;
    }
    /**
     * @var \SD\TaskBundle\Entity\Stage
     */
    private $stage;


    /**
     * Set stage
     *
     * @param \SD\TaskBundle\Entity\Stage $stage
     *
     * @return TaskEndDate
     */
    public function setStage(\SD\TaskBundle\Entity\Stage $stage = null)
    {
        $this->stage = $stage;

        return $this;
    }

    /**
     * Get stage
     *
     * @return \SD\TaskBundle\Entity\Stage 
     */
    public function getStage()
    {
        return $this->stage;
    }
}