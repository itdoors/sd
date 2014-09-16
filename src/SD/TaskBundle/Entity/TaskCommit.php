<?php

namespace SD\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskCommit
 */
class TaskCommit
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \SD\TaskBundle\Entity\Stage
     */
    private $stage;

    /**
     * @var \SD\TaskBundle\Entity\TaskUserRole
     */
    private $taskUserRole;


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
     * Set stage
     *
     * @param \SD\TaskBundle\Entity\Stage $stage
     * @return TaskCommit
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

    /**
     * Set taskUserRole
     *
     * @param \SD\TaskBundle\Entity\TaskUserRole $taskUserRole
     * @return TaskCommit
     */
    public function setTaskUserRole(\SD\TaskBundle\Entity\TaskUserRole $taskUserRole = null)
    {
        $this->taskUserRole = $taskUserRole;
    
        return $this;
    }

    /**
     * Get taskUserRole
     *
     * @return \SD\TaskBundle\Entity\TaskUserRole 
     */
    public function getTaskUserRole()
    {
        return $this->taskUserRole;
    }
}