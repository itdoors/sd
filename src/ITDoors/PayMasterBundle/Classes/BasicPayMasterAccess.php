<?php

namespace ITDoors\PayMasterBundle\Classes;

use ITDoors\PayMasterBundle\Interfaces\PayMasterAccessInterface;

/**
 * BasicPayMasterAccess class
 */
class BasicPayMasterAccess implements PayMasterAccessInterface
{
    /**
     * @return bool
     */
    public function canAdd ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeAll ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canRemove ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canChangeStatus ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canChangeIsAcceptance ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canChangeToPay ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canEditBank ()
    {
        return false;
    }
}
