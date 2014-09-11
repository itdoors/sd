<?php

namespace SD\TaskBundle\Classes;

use SD\TaskBundle\Interfaces\TaskRoleInterface;
use SD\TaskBundle\Entity\Task;
use SD\TaskBundle\Entity\TaskUserRole;
/**
 * ControllerTaskRole class
 */
class ControllerTaskRole extends BasicTaskRole
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
    public function canSetUndone() {
        if ($this->isViewed()) {
            if ($this->stage == 'created' || $this->stage == 'performing' || $this->stage == 'date request' || $this->stage == 'checking') {
                return true;
            }
        }

        return false;
    }

    public function canSetClosed() {
        if ($this->isViewed()) {
            if ($this->stage == 'created' || $this->stage == 'performing' || $this->stage == 'date request' || $this->stage == 'checking') {
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
