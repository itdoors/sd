<?php

namespace SD\DashboardBundle\Classes;

/**
 * DashboardAccessFactory class
 */
class DashboardAccessFactory
{
    /**
     * @param mixed $roles
     *
     * @return ComparatorDashboardAccess
     */
    public static function createAccess($roles)
    {
        $access = array();
        foreach ($roles as $role) {
            if ($role == 'sales') {
                $access[] = new SalesDashboardAccess();
            } elseif ($role == 'sales_admin') {
                $access[] = new SalesAdminDashboardAccess();
            } else {
                $access[] = new DashboardBasicAccess();
            }
        }

        return new ComparatorDashboardAccess($access);
    }
}
