<?php

namespace ITDoors\PayMasterBundle\Classes;

/**
 * PayMasterAccessFactory class
 */
class PayMasterAccessFactory
{
    /**
     * @param mixed $roles
     *
     * @return ComparatorPayMasterAccess
     */
    public static function createAccess($roles)
    {
        $access = array();
        $access[] = new BasicPayMasterAccess();
        foreach ($roles as $role) {
            if ($role == 'payMaster') {
                $access[] = new PayMasterAccess();
            } elseif ($role == 'payMasterControlling') {
                $access[] = new PayMasterControllingAccess();
            }
        }

        return new ComparatorPayMasterAccess($access);
    }
}
