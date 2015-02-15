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
    public function canExportReportToExcel ()
    {
        return true;
    }
}
