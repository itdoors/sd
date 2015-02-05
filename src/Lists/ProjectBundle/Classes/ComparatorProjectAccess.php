<?php

namespace Lists\ProjectBundle\Classes;
use Lists\ProjectBundle\Interfaces\ProjectAccessInterface;

/**
 * ComparatorProjectAccess class
 */
class ComparatorProjectAccess extends BasicProjectAccess
{

    protected $accesses;

    /**
     * @param ProjectAccessInterface[] $accesses
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
    public function canCreate ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canCreate()) {
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
    public function canChangeManager ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canChangeManager()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canChangeManagerProject ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canChangeManagerProject()) {
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
