<?php

namespace SD\TaskBundle\Classes;

use SD\TaskBundle\Interfaces\TaskRoleInterface;
/**
 * AuthorTaskRole class
 */
class AuthorTaskRole implements TaskRoleInterface
{
    public $task;
    public $stage;
    public function isViewed() {

    }
    public function canSetDone() {
        return false;
    }
    public function canSetUndone() {
        return false;
    }
    public function canSetClosed() {
        return false;
    }
    public function canUploadFiles() {
        return true;
    }
    public function canLeaveComment() {
        return false;
    }
}