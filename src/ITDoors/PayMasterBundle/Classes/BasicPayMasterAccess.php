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
}
