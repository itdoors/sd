<?php

namespace Lists\HandlingBundle\Classes;

/**
 * ReportHandlingAccess class
 */
class ReportHandlingAccess extends BasicHandlingAccess
{
    /**
     * @return bool
     */
    public function canSeeReport ()
    {
        return true;
    }
}
