<?php

namespace Lists\HandlingBundle\Classes;

/**
 * SalesAdminHandlingAccess class
 */
class SalesAdminHandlingAccess extends BasicHandlingAccess
{
    /**
     * @return bool
     */
    public function canSeeReport ()
    {
        return true;
    }
}
