<?php

namespace Lists\DepartmentBundle\Classes;

/**
 * DepartmentAccessFactory class
 */
class DepartmentAccessFactory
{
    /**
     * @param mixed $roles
     *
     * @return ComparatorDepartmentAccess
     */
    public static function createAccess($roles)
    {
        $access = array();
        foreach ($roles as $role) {
            if ($role == 'hradmin') {
                $access[] = new HradminDepartmentAccess();
            } else {
                $access[] = new BasicDepartmentAccess();
            }
        }

        return new ComparatorDepartmentAccess($access);
    }
}
