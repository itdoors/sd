<?php

namespace Lists\HandlingBundle\Classes;

/**
 * SalesHandlingAccess class
 */
class SalesHandlingAccess extends BasicHandlingAccess
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
    public function canSeeListMy ()
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
