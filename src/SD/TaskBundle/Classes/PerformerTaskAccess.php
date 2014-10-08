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
    public function canSetDone()
    {
        if ($this->isViewed()) {
            if ($this->getStage() == 'created' ||
                $this->getStage() == 'performing' ||
                $this->getStage() == 'date request') {

                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canMakeDateRequest()
    {
        if ($this->isViewed()) {
            if ($this->getStage() == 'created' || $this->getStage() == 'performing') {

                return true;
            }
        }

        return false;
    }


    /**
     * @return bool
     */
    public function canUploadFiles()
    {
        if ($this->isViewed()) {

            return true;
        }
    }

    /**
     * @return bool
     */
    public function canLeaveComment()
    {
        if ($this->isViewed() && ($this->canSetDone() || $this->canMakeDateRequest())) {

            return true;
        }
    }
}
