<?php

namespace SD\DashboardBundle\Classes;

/**
 * SalesDashboardAccess class
 */
class SalesDashboardAccess extends DashboardBasicAccess
{
    /**
     * @return bool
     */
    public function canSeeMyActions ()
    {
        return true;
    }
}
