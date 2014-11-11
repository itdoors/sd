<?php

namespace SD\DashboardBundle\Interfaces;

/**
 * OrganizationAccessInterface interface
 */
interface DashboardAccessInterface
{
    /**
     * @return bool
     */
    public function canSeeTasksCalendar();
    /**
     * @return bool
     */
    public function canSeeMyActions();
    /**
     * @return bool
     */
    public function canSeeInvoiceNeedStitch();
    /**
     * @return bool
     */
    public function canSeeInvoiceExpectedPayments();
    /**
     * @return bool
     */
    public function canSeeCustomersWithoutContacts();
    /**
     * @return bool
     */
    public function canSeeDogovorListDanger();
}
