<?php

namespace Lists\HandlingBundle\Classes;
use Lists\HandlingBundle\Entity\Handling;

/**
 * HandlingAccessFactory class
 */
class HandlingAccessFactory
{
    /**
     * createAccess
     * 
     * @param mixed    $roles
     * @param Handling $handling
     *
     * @return ComparatorHandlingAccess
     */
    public static function createAccess($roles, Handling $handling = null)
    {
        $access = array();
        $access[] = new BasicHandlingAccess();
        foreach ($roles as $role) {
            if ($role == 'manager_organization') {
                $access[] = new ManagerOrganizationAccess();
            } else {
                $className = 'Lists\HandlingBundle\Classes\\'.$role.'HandlingAccess';
                $access[] = new $className();            
            }
        }

        return new ComparatorHandlingAccess($access, $handling);
    }
}
