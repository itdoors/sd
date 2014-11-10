<?php

namespace Lists\HandlingBundle\Classes;

/**
 * ComparatorHandlingAccess class
 */
class ComparatorHandlingAccess extends BasicHandlingAccess
{

    protected $accesses;

    /**
     * @param \Lists\HandlingBundle\Interfaces\HandlingAccessInterface[]   $accesses
     */
    public function __construct($accesses)
    {
        $this->accesses = $accesses;
    }
    /**
     * @return bool
     */
    public function canSee ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSee()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeReport ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeReport()) {
                return true;
            }
        }

        return false;
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
    public function canAddManager ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canAddManager()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canEdit ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canEdit()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeListMy ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeListMy()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeList ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeList()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canExportToExelAll ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canExportToExelAll()) {
                return true;
            }
        }

        return false;
    }
}
