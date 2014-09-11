<?php

namespace SD\TaskBundle\Classes;

use SD\TaskBundle\Interfaces\TaskRoleInterface;
use SD\TaskBundle\Entity\Task;
use SD\TaskBundle\Entity\TaskUserRole;

/**
 * BasicTaskRole class
 */
class BasicTaskRole implements TaskRoleInterface
{
    /**
     * @var \SD\TaskBundle\Entity\Stage
     */
    protected $stage;

    /**
     * @var bool
     */
    protected  $isViewed;

    /**
     * @param TaskUserRole $taskUserRole
     */
    public function __construct(TaskUserRole $taskUserRole) {
        $this->stage = $taskUserRole->getTask()->getStage();
        $this->isViewed = $taskUserRole->getIsViewed();
    }

    /**
     * @return bool
     */
    public function isViewed() {
        return $this->getIsViewed();
    }

    /**
     * @return bool
     */
    public function canSetDone() {
        return false;
    }

    /**
     * @return bool
     */
    public function canSetUndone() {
        return false;
    }

    /**
     * @return bool
     */
    public function canSetClosed() {
        return false;
    }

    /**
     * @return bool
     */
    public function canUploadFiles() {
        return true;
    }

    /**
     * @return bool
     */
    public function canLeaveComment() {
        if ($this->canSetClosed() || $this->canSetDone() || $this->canSetUndone()) {
            return true;
        }

        return false;
    }

    /**
     * @return Task
     */
    public function getStage() {
        return $this->stage;
    }

    /**
     * @return bool
     */
    public function getIsViewed() {
        return $this->isViewed;
    }
}
