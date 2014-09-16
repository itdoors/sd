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
    /**
     * @var \SD\TaskBundle\Entity\Stage
     */
    protected $stage;

    /**
     * @var bool
     */
    protected $isViewed;

    /**
     * @param Stage $stage
     * @param bool  $isViewed
     */
    public function __construct(Stage $stage, $isViewed)
    {
        $this->stage = $stage;
        $this->isViewed = $isViewed;
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
        return $this->stage;
    }

    /**
     * @return bool
     */
    public function getIsViewed()
    {
        return $this->isViewed;
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

}
