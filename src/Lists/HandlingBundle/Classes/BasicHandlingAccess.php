<?php

namespace Lists\HandlingBundle\Classes;

use Lists\HandlingBundle\Interfaces\HandlingAccessInterface;

/**
 * BasicHandlingAccess class
 */
class BasicHandlingAccess implements HandlingAccessInterface
{
    /**
     * @return bool
     */
    public function canSee ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeReport ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canEdit ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canAdd ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canAddManager ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeListMy ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeList ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canExportToExelAll ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canExportReportToExcel ()
    {
        return false;
    }
}
