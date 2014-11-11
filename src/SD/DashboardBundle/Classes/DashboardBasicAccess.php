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
    public function canSeeTasksCalendar ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canSeeMyActions ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeInvoiceNeedStitch ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeInvoiceExpectedPayments ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeCustomersWithoutContacts ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeDogovorListDanger ()
    {
        return false;
    }
}
