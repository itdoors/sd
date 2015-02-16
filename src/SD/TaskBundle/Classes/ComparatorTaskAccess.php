<?php

namespace SD\TaskBundle\Classes;

use SD\TaskBundle\Entity\Stage;
use SD\TaskBundle\Interfaces\TaskAccessInterface;
use SD\TaskBundle\Entity\Task;
use SD\TaskBundle\Entity\TaskUserRole;

/**
 * BasicTaskAccess class
 */
class ComparatorTaskAccess extends BasicTaskAccess
{

    protected $accesses;

    /**
     * @param \SD\TaskBundle\Interfaces\TaskAccessInterface[]   $accesses
     */
    public function __construct($accesses)
    {
        $this->accesses = $accesses;
    }

    /**
     * @return bool
     */
    public function isViewed()
    {
        foreach ($this->accesses as $access) {
            if (!$access->isViewed()) {
                return false;
            }
        }

        return true;

    }

    /**
     * @return bool
     */
    public function canSetDone()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSetDone()) {
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
        foreach ($this->accesses as $access) {
            if ($access->canSetUndone()) {
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

        foreach ($this->accesses as $access) {
            if ($access->canSetClosed()) {
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

        foreach ($this->accesses as $access) {
            if ($access->canUploadFiles()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canSetChecking()
    {

        foreach ($this->accesses as $access) {
            if ($access->canSetChecking()) {
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

        foreach ($this->accesses as $access) {
            if ($access->canMakeDateRequest()) {
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

        foreach ($this->accesses as $access) {
            if ($access->canAnswerDateRequest()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canLeaveComment()
    {
        foreach ($this->accesses as $access) {
            if ($access->canLeaveComment()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canSignUp()
    {

        foreach ($this->accesses as $access) {
            if ($access->canSignUp()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canRefuseSignUp()
    {

        foreach ($this->accesses as $access) {
            if ($access->canRefuseSignUp()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canEditHeader()
    {

        foreach ($this->accesses as $access) {
            if ($access->canEditHeader()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canEditDescription()
    {

        foreach ($this->accesses as $access) {
            if ($access->canEditDescription()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canEditEndDate()
    {

        foreach ($this->accesses as $access) {
            if ($access->canEditEndDate()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canAddResolution()
    {

        foreach ($this->accesses as $access) {
            if ($access->canAddResolution()) {
                return true;
            }
        }

        return false;
    }


    /**
     * @return null|Stage
     */
    public function getLastCommitStage()
    {
        foreach ($this->accesses as $access) {
            if ($access->getLastCommitStage()) {
                return $access->getLastCommitStage();
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canDeleteFile()
    {
        foreach ($this->accesses as $access) {
            if ($access->canDeleteFile()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canAddViewer()
    {
        foreach ($this->accesses as $access) {
            if ($access->canAddViewer()) {
                return true;
            }
        }

        return false;

    }
    /**
     * @return bool
     */
    public function canAddMatcher()
    {
        foreach ($this->accesses as $access) {
            if ($access->canAddMatcher()) {
                return true;
            }
        }

        return false;

    }
    /**
     * @return bool
     */
    public function canReply()
    {
        foreach ($this->accesses as $access) {
            if ($access->canReply()) {
                return true;
            }
        }

        return false;

    }
}
