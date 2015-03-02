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
    public function canSeeProjectStateTender ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canEditProjectStateTender ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canEditProjectSimple()
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
    /**
     * @return bool
     */
    public function canAddFiles ()
    {
        return true;
    }
}
