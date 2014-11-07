<?php

namespace Lists\HandlingBundle\Classes;

/**
 * ManagerHandlingAccess class
 */
class ManagerHandlingAccess extends BasicHandlingAccess
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
    /**
     * @return bool
     */
    public function canAddManager ()
    {
        return true;
    }
}
