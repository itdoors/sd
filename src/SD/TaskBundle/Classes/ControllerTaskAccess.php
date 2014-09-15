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
    public function canSetUndone()
    {
        if ($this->isViewed()) {
            if ($this->stage == 'created' || $this->stage == 'performing' ||
                $this->stage == 'date request' || $this->stage == 'checking') {

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
            if ($this->stage == 'created' || $this->stage == 'performing' ||
                $this->stage == 'date request' || $this->stage == 'checking') {

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
        if ($this->stage == 'checking') {

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
            if ($this->stage == 'created' || $this->stage == 'performing') {

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
            if ($this->stage == 'date request') {

                return true;
            }
        }

        return false;
    }
}
