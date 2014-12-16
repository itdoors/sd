<?php

namespace ITDoors\PayMasterBundle\Services;

use ITDoors\PayMasterBundle\Classes\PayMasterAccessFactory;
use SD\UserBundle\Entity\User;

/**
 * PayMasterService class
 */
class PayMasterService
{
    /**
     * checkAccess
     * 
     * @param User         $user
     * 
     * @return mixed[]
     */
    public function checkAccess(User $user)
    {
        $role = array();
        if ($user->hasRole('ROLE_PAY_MASTER')) {
            $role[] = 'payMaster';
        }
        if ($user->hasRole('ROLE_PAY_MASTER_CONTROLLING')) {
            $role[] = 'payMasterControlling';
        }

        return PayMasterAccessFactory::createAccess($role);
    }
}
