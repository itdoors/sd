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
            if ($role == 'controlling') {
                $access[] = new ControllingDashboardAccess();
            } elseif ($role == 'sales') {
                $access[] = new SalesDashboardAccess();
            } elseif ($role == 'dogovor_admin') {
                $access[] = new DogovorAdminDashboardAccess();
            } elseif ($role == 'sales_admin') {
                $access[] = new SalesAdminDashboardAccess();
            } else {
                $access[] = new DashboardBasicAccess();
            }
        }

        return new ComparatorDashboardAccess($access);
    }
}
