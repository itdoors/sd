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
    /**
     * @return bool
     */
    public function canSeeAll ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeAll()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canRemove ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canRemove()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canChangeStatus ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canChangeStatus()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canChangeIsAcceptance ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canChangeIsAcceptance()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canChangeToPay ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canChangeToPay()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canEditBank ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canEditBank()) {
                return true;
            }
        }

        return false;
    }
}
