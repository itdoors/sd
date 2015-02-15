<?php

namespace ITDoors\PayMasterBundle\Classes;

/**
 * PayMasterControllingAccess class
 */
class PayMasterControllingAccess extends BasicPayMasterAccess
{
    /**
     * @return bool
     */
    public function canAdd ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canSeeAll ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canChangeIsAcceptance ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canChangeToPay ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canEditBank ()
    {
        return true;
    }
}
