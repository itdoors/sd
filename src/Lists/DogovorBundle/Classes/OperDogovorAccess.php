<?php

namespace Lists\DogovorBundle\Classes;

/**
 * OperDogovorAccess class
 */
class OperDogovorAccess extends BasicDogovorAccess
{
    /**
     * @return bool
     */
    public function canSee ()
    {
        return true;
    }
}
