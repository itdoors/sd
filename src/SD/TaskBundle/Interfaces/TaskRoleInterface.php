<?php

namespace SD\TaskBundle\Interfaces;

/**
 * TaskRoleInterface interface
 */
interface TaskRoleInterface
{
    public function isViewed();
    public function canSetDone();
    public function canSetUndone();
    public function canSetClosed();
    public function canUploadFiles();
    public function canLeaveComment();
}