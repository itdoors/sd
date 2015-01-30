<?php

namespace SD\DashboardBundle\Classes;

use SD\DashboardBundle\Interfaces\DashboardAccessInterface;

/**
 * BasicDashboardAccess class
 */
class BasicDashboardAccess implements DashboardAccessInterface
{
    /**
     * @return bool
     */
    public function canSeeMyActions ()
    {
        return false;
    }
}
