<?php

namespace ITDoors\PayMasterBundle\Classes;

/**
 * PayMasterAccess class
 */
class PayMasterAccess extends BasicPayMasterAccess
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
    public function canRemove ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canChangeStatus ()
    {
        return true;
    }
}
