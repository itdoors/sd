<?php

namespace SD\DashboardBundle\Classes;

/**
 * SalesAdminDashboardAccess class
 */
class SalesAdminDashboardAccess extends DashboardBasicAccess
{
    /**
     * @return bool
     */
    public function canSeeMyActions ()
    {
        return true;
    }
}
