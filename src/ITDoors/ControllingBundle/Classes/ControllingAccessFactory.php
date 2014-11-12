<?php

namespace ITDoors\ControllingBundle\Classes;

/**
 * ControllingAccessFactory class
 */
class ControllingAccessFactory
{
    /**
     * @param mixed $roles
     *
     * @return ComparatorControllingAccess
     */
    public static function createAccess($roles)
    {
        $access = array();
        foreach ($roles as $role) {
            if ($role == 'controlling') {
                $access[] = new ControllingAccess();
            } elseif ($role == 'controlling_oper') {
                $access[] = new ControllingOperAccess();
            } elseif ($role == 'controlling_viewer') {
                $access[] = new ControllingViewerAccess();
            } else {
                $access[] = new BasicControllingAccess();
            }
        }

        return new ComparatorControllingAccess($access);
    }
}
