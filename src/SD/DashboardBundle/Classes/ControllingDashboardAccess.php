<?php

namespace SD\DashboardBundle\Classes;

/**
 * ControllingDashboardAccess class
 */
class ControllingDashboardAccess extends DashboardBasicAccess
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
    public function canSeeInvoiceNeedStitch ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canSeeInvoiceExpectedPayments ()
    {
        return true;
    }
}
