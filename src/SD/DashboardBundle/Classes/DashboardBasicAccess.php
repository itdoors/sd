<?php

namespace SD\DashboardBundle\Classes;

use SD\DashboardBundle\Interfaces\DashboardAccessInterface;

/**
 * DashboardBasicAccess class
 */
class DashboardBasicAccess implements DashboardAccessInterface
{
    /**
     * @return bool
     */
    public function canSeeMyActions ()
    {
        return false;
    }
}
