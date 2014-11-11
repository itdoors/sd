<?php

namespace SD\DashboardBundle\Classes;

/**
 * ControllingOperDashboardAccess class
 */
class ControllingOperDashboardAccess extends DashboardBasicAccess
{
    /**
     * @return bool
     */
    public function canSeeTasksCalendar ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canSeeCustomersWithoutContacts ()
    {
        return true;
    }
}
