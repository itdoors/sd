<?php

namespace SD\TaskBundle\Classes;

use SD\TaskBundle\Interfaces\TaskRoleInterface;
use SD\TaskBundle\Entity\Task;
use SD\TaskBundle\Entity\TaskUserRole;
/**
 * PerformerTaskRole class
 */
class PerformerTaskRole extends BasicTaskRole
{
    /**
     * @return bool
     */
    public function canSetDone() {
        if ($this->isViewed()) {
            if ($this->stage == 'created' || $this->stage == 'performing' || $this->stage == 'date request') {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canUploadFiles() {
        if ($this->isViewed()) {

            return true;
        }
    }

    /**
     * @return bool
     */
    public function canLeaveComment() {
        if ($this->isViewed()) {

            return true;
        }
    }
}
