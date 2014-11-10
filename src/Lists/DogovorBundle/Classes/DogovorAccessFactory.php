<?php

namespace Lists\DogovorBundle\Classes;

/**
 * DogovorAccessFactory class
 */
class DogovorAccessFactory
{
    /**
     * @param mixed $roles
     *
     * @return ComparatorDogovorAccess|BasicDogovorAccess
     */
    public static function createAccess($roles)
    {
        $access = array();
        foreach ($roles as $role) {
            if ($role == 'admin') {
                $access[] = new AdminDogovorAccess();
            } elseif ($role == 'manager_organization') {
                $access[] = new ManagerOrganizationAccess();
            } elseif ($role == 'controlling') {
                $access[] = new ControllingDogovorAccess();
            } elseif ($role == 'oper') {
                $access[] = new OperDogovorAccess();
            } elseif ($role == 'sales') {
                $access[] = new SalesDogovorAccess();
            } elseif ($role == 'dogovor_viewer') {
                $access[] = new ViewerDogovorAccess();
            } else {
                $access[] = new BasicDogovorAccess();
            }
        }

        return new ComparatorDogovorAccess($access);
    }
}
