<?php

namespace SD\TaskBundle\Interfaces;

/**
 * TaskAccessInterface interface
 */
interface TaskAccessInterface
{
    public function isViewed();
    public function canSetDone();
    public function canSetUndone();
    public function canSetClosed();
    public function canUploadFiles();
    public function canLeaveComment();
    public function canSetChecking();
    public function canAnswerDateRequest();


}