<?php

namespace ITDoors\ControllingBundle\Classes;

/**
 * ControllingAccess class
 */
class ControllingAccess extends BasicControllingAccess
{
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
    public function canSeeAll ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canAddNote ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canAddResponsible ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canChangeStatusCourt ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canChangeStatus ()
    {
        return true;
    }
}
