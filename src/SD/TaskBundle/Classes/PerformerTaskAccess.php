<?php

namespace SD\TaskBundle\Classes;

use SD\TaskBundle\Entity\Task;
use SD\TaskBundle\Entity\TaskUserRole;
/**
 * PerformerTaskAccess class
 */
class PerformerTaskAccess extends BasicTaskAccess
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
    public function canMakeDateRequest() {
        if ($this->isViewed()) {
            if ($this->stage == 'created' || $this->stage == 'performing') {

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
