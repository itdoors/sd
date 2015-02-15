<?php

namespace Lists\DogovorBundle\Classes;

/**
 * ControllingDogovorAccess class
 */
class ControllingDogovorAccess extends BasicDogovorAccess
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
    public function canSeeList ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canSeeDanger ()
    {
        return true;
    }
}
