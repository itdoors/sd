<?php

namespace SD\DashboardBundle\Classes;

/**
 * SalesAdminDashboardAccess class
 */
class SalesAdminDashboardAccess extends BasicDashboardAccess
{
    /**
     * @return bool
     */
    public function canSeeMyActions ()
    {
        return true;
    }
}
