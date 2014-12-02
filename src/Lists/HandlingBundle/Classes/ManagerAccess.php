<?php

namespace Lists\HandlingBundle\Classes;

/**
 * ManagerAccess class
 */
class ManagerAccess extends BasicHandlingAccess
{
    /**
     * @return bool
     */
    public function canSee ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canEdit ()
    {
        return true;
    }
}
