<?php

namespace Lists\OrganizationBundle\Classes;

/**
 * OrganizationAccessFactory class
 */
class OrganizationAccessFactory
{
    /**
     * @param mixed $roles
     *
     * @return ComparatorOrganizationAccess|BasicOrganizationAccess
     */
    public static function createAccess($roles)
    {
        $access = array();
        foreach ($roles as $role) {
            if ($role == 'managerOrganization') {
                $access[] = new ManagerOrganizationAccess();
            } elseif ($role == 'controlling') {
                $access[] = new ControllingOrganizationAccess();
            } elseif ($role == 'controlling_oper') {
                $access[] = new ControllingOperOrganizationAccess();
            } elseif ($role == 'sales') {
                $access[] = new SalesOrganizationAccess();
            } elseif ($role == 'sales_admin') {
                $access[] = new SalesAdminOrganizationAccess();
            } elseif ($role == 'dogovor_admin') {
                $access[] = new DogovorAdminOrganizationAccess();
            } elseif ($role == 'GosTenderAdmin') {
                $access[] = new GosTenderAdminOrganizationAccess();
            } else {
                $access[] = new BasicOrganizationAccess();
            }
        }

        return new ComparatorOrganizationAccess($access);
    }
}
