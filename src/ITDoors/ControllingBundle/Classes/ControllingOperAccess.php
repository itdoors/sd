<?php

namespace ITDoors\ControllingBundle\Classes;

/**
 * ControllingOperAccess class
 */
class ControllingOperAccess extends BasicControllingAccess
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
    public function canAddNote ()
    {
        return true;
    }
}
