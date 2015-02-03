<?php

namespace SD\TaskBundle\Classes;

use SD\TaskBundle\Entity\Stage;
use SD\TaskBundle\Interfaces\TaskAccessInterface;
use SD\TaskBundle\Entity\Task;
use SD\TaskBundle\Entity\TaskUserRole;

/**
 * BasicTaskAccess class
 */
class BasicTaskAccess implements TaskAccessInterface
{

    protected $taskUserRole;
    protected $em;

    /**
     * @param Object       $em
     * @param TaskUserRole $taskUserRole
     */
    public function __construct($em, $taskUserRole)
    {
        $this->taskUserRole = $taskUserRole;
        $this->em = $em;
    }

    /**
     * @return bool
     */
    public function isViewed()
    {

        return $this->getIsViewed();
    }

    /**
     * @return bool
     */
    public function canSetDone()
    {

        return false;
    }

    /**
     * @return bool
     */
    public function canSetUndone()
    {

        return false;
    }

    /**
     * @return bool
     */
    public function canSetClosed()
    {

        return false;
    }

    /**
     * @return bool
     */
    public function canUploadFiles()
    {

        return true;
    }

    /**
     * @return bool
     */
    public function canSetChecking()
    {

        return false;
    }

    /**
     * @return bool
     */
    public function canMakeDateRequest()
    {

        return false;
    }

    /**
     * @return bool
     */
    public function canAnswerDateRequest()
    {

        return false;
    }

    /**
     * @return bool
     */
    public function canLeaveComment()
    {
        if ($this->canSetClosed() || $this->canSetDone() || $this->canSetUndone() || $this->canMakeDateRequest()) {
            return true;
        }

        return false;
    }

    /**
     * @return Task
     */
    public function getStage()
    {
        return $this->taskUserRole->getTask()->getStage();
    }

    /**
     * @return bool
     */
    public function getIsViewed()
    {
        return $this->taskUserRole->getIsViewed();
    }

    /**
     * @return bool
     */
    public function canSignUp()
    {

        return false;
    }

    /**
     * @return bool
     */
    public function canRefuseSignUp()
    {

        return false;
    }

    /**
     * @return bool
     */
    public function canEditHeader()
    {

        return false;
    }
    /**
     * @return bool
     */
    public function canEditDescription()
    {

        return false;
    }
    /**
     * @return bool
     */
    public function canEditEndDate()
    {

        return false;
    }

    /**
     * @return bool
     */
    public function canAddResolution()
    {

        return false;
    }

    /**
     * @return bool
     */
    public function canAddMatcher()
    {

        return false;
    }

    /**
     * @return bool
     */
    public function canReply()
    {

        return false;
    }


    /**
     * @return null|Stage
     */
    public function getLastCommitStage()
    {
        $taskUserRole = $this->taskUserRole;

        $taskCommitRepo = $this->em->getRepository('SDTaskBundle:TaskCommit');
        $taskCommit = $taskCommitRepo->findOneBy(array(
            'taskUserRole' => $taskUserRole
        ), array(
            'id' => 'DESC'
        ));

        if (!$taskCommit) {
            return null;
        } else {
            return $taskCommit->getStage();
        }
    }

    /**
     * @return bool
     */
    public function canDeleteFile()
    {
        if ($this->getStage() != 'closed'
            && $this->getStage() != 'done'
            && $this->getStage() != 'undone') {

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canAddViewer()
    {
        if ($this->getStage() != 'closed'
            && $this->getStage() != 'done'
            && $this->getStage() != 'undone') {

            return true;
        }

        return false;

    }
}
