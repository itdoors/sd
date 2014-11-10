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
    public function canSee ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canSeeReport ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canAdd ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canSeeListMy ()
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
    public function canExportToExelAll ()
    {
        return true;
    }
}
