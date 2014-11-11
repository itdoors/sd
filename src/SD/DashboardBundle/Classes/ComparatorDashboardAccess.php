<?php

namespace SD\DashboardBundle\Classes;

/**
 * ComparatorDashboardAccess class
 */
class ComparatorDashboardAccess extends DashboardBasicAccess
{

    protected $accesses;

    /**
     * @param \SD\DashboardBundle\Interfaces\DashboardAccessInterface[]   $accesses
     */
    public function __construct($accesses)
    {
        $this->accesses = $accesses;
    }
    /**
     * @return bool
     */
    public function canSeeTasksCalendar ()
    {
        foreach ($this->accesses as $access) {
            if (!$access->canSeeTasksCalendar()) {
                return false;
            }
        }

        return true;
    }
    /**
     * @return bool
     */
    public function canSeeMyActions ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeTasksCalendar()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeInvoiceNeedStitch ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeInvoiceNeedStitch()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeInvoiceExpectedPayments ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeInvoiceExpectedPayments()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeCustomersWithoutContacts ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeInvoiceExpectedPayments()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeDogovorListDanger ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeDogovorListDanger()) {
                return true;
            }
        }

        return false;
    }
}
