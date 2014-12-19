<?php

namespace Lists\HandlingBundle\Classes;

/**
 * HandlingAccessFactory class
 */
class HandlingAccessFactory
{
    /**
     * @param mixed $roles
     *
     * @return ComparatorDogovorAccess|BasicHandlingAccess
     */
    public static function createAccess($roles)
    {
        $access = array();
        foreach ($roles as $role) {
            if ($role == 'sales_admin') {
                $access[] = new SalesAdminHandlingAccess();
            } elseif ($role == 'manager_organization') {
                $access[] = new ManagerOrganizationAccess();
            } elseif ($role == 'manager_project') {
                $access[] = new ManagerHandlingAccess();
            } elseif ($role == 'manager') {
                $access[] = new ManagerAccess();
            } elseif ($role == 'report') {
                $access[] = new ReportHandlingAccess();
            } else {
                $access[] = new BasicHandlingAccess();
            }
        }

        return new ComparatorHandlingAccess($access);
    }
}
