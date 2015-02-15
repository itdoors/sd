<?php

namespace SD\DashboardBundle\Classes;

/**
 * SalesDashboardAccess class
 */
class SalesDashboardAccess extends BasicDashboardAccess
{
    /**
     * @return bool
     */
    public function canSeeMyActions ()
    {
        return true;
    }
}
