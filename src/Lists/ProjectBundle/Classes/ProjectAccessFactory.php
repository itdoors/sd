<?php

namespace Lists\ProjectBundle\Classes;

/**
 * ProjectAccessFactory class
 */
class ProjectAccessFactory
{
    /**
     * createAccess
     * 
     * @param mixed    $roles
     *
     * @return ComparatorProjectAccess
     */
    public static function createAccess($roles)
    {
        $access = array();
        $access[] = new BasicProjectAccess();
        foreach ($roles as $role) {
                $className = 'Lists\ProjectBundle\Classes\\'.$role.'ProjectAccess';
                $access[] = new $className();            
        }

        return new ComparatorProjectAccess($access);
    }
}
