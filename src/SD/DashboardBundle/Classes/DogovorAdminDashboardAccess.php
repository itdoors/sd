<?php

namespace SD\DashboardBundle\Classes;

/**
 * DogovorAdminDashboardAccess class
 */
class DogovorAdminDashboardAccess extends DashboardBasicAccess
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
    public function canSeeDogovorListDanger ()
    {
        return true;
    }
}
