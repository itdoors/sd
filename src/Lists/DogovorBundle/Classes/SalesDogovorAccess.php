<?php

namespace Lists\DogovorBundle\Classes;

/**
 * SalesDogovorAccess class
 */
class SalesDogovorAccess extends BasicDogovorAccess
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
    public function canSeeDanger ()
    {
        return true;
    }
}
