<?php

namespace SD\TaskBundle\Interfaces;

/**
 * TaskAccessInterface interface
 */
interface TaskAccessInterface
{
    /**
     * @return bool
     */
    public function isViewed();

    /**
     * @return bool
     */
    public function canSetDone();
    /**
     * @return bool
     */
    public function canSetUndone();
    /**
     * @return bool
     */
    public function canSetClosed();
    /**
     * @return bool
     */
    public function canUploadFiles();
    /**
     * @return bool
     */
    public function canLeaveComment();
    /**
     * @return bool
     */
    public function canSetChecking();
    /**
     * @return bool
     */
    public function canAnswerDateRequest();

    /**
     * @return bool
     */
    public function canSignUp();

    /**
     * @return bool
     */
    public function canRefuseSignUp();
}
