<?php

namespace SD\TaskBundle\Classes;

use SD\TaskBundle\Entity\Task;
use SD\TaskBundle\Entity\TaskUserRole;

/**
 * ControllerTaskAccess class
 */
class ControllerTaskAccess extends BasicTaskAccess
{
    /**
     * @return bool
     */
    public function canSetDone()
    {
/*
        if ($this->isViewed()) {
            if ($this->getStage() == 'created' ||
                $this->getStage() == 'performing' ||
                $this->getStage() == 'date request') {

                return true;
            }
        }
*/

        return false;
    }

    /**
     * @return bool
     */
    public function canSetUndone()
    {
        if ($this->isViewed()) {
            if ($this->getStage() == 'created' || $this->getStage() == 'performing' ||
                $this->getStage() == 'date request' || $this->getStage() == 'checking') {

                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canSetClosed()
    {
        if ($this->isViewed()) {
            if ($this->getStage() == 'created' || $this->getStage() == 'performing' ||
                $this->getStage() == 'date request' || $this->getStage() == 'checking') {

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
        if ($this->isViewed()) {

            return true;
        }
    }

    /**
     * @return bool
     */
    public function canSetChecking()
    {
        if ($this->getStage() == 'checking') {

            return true;
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
    public function canAnswerDateRequest()
    {
        if ($this->isViewed()) {
            if ($this->getStage() == 'date request') {

                return true;
            }
        }

        return false;
    }
}
