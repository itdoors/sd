<?php

namespace Lists\ProjectBundle\Classes;

/**
 * ManagerProjectAccess class
 */
class ManagerProjectAccess extends BasicProjectAccess
{
    /**
     * @return bool
     */
    public function canSeeStateTender ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canEditStateTender ()
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
    public function canCloseProject ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canAddMessage ()
    {
        return true;
    }
}
