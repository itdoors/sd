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
    public function canSeeTasksCalendar ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canSeeMyActions ()
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
