<?php

namespace Lists\DogovorBundle\Classes;

/**
 * ViewerDogovorAccess class
 */
class ViewerDogovorAccess extends BasicDogovorAccess
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
}
