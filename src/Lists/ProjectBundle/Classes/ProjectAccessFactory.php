<?php

namespace Lists\ProjectBundle\Classes;

use Lists\ProjectBundle\Entity\ProjectStateTender;

/**
 * ProjectAccessFactory class
 */
class ProjectAccessFactory
{
    /**
     * createAccess
     * 
     * @param mixed                $roles
     * @param ProjectStateTender   $object
     *
     * @return ComparatorProjectAccess
     */
    public static function createAccess($roles, $object = null)
    {
        $access = array();
        $access[] = new BasicProjectAccess();
        foreach ($roles as $role) {
                $className = 'Lists\ProjectBundle\Classes\\'.$role.'ProjectAccess';
                $access[] = new $className();            
        }

        return new ComparatorProjectAccess($access, $object);
    }
}
