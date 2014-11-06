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
            } elseif ($role == 'oper') {
                $access[] = new OperDogovorAccess();
            } else {
                $access[] = new BasicDogovorAccess();
            }
        }

        return new ComparatorDogovorAccess($access);
    }
}
