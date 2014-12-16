<?php

namespace ITDoors\PayMasterBundle\Classes;

/**
 * ComparatorPayMasterAccess class
 */
class ComparatorPayMasterAccess extends BasicPayMasterAccess
{

    protected $accesses;

    /**
     * @param \ITDoors\PayMasterBundle\Interfaces\PayMasterAccessInterface[]   $accesses
     */
    public function __construct($accesses)
    {
        $this->accesses = $accesses;
    }
    /**
     * @return bool
     */
    public function canAdd ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canAdd()) {
                return true;
            }
        }

        return false;
    }
}
