<?php

namespace Lists\HandlingBundle\Classes;

/**
 * ManagerProjectHandlingAccess class
 */
class ManagerProjectHandlingAccess extends BasicHandlingAccess
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
