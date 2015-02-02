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
            $className = 'SD\DashboardBundle\Classes\\'.$role.'DashboardAccess';
            $access[] = new $className();
        }

        return new ComparatorDashboardAccess($access);
    }
}
