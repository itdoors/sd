<?php

namespace Lists\ProjectBundle\Classes;

use Lists\ProjectBundle\Interfaces\ProjectAccessInterface;
use Lists\ProjectBundle\Entity\StateTender;

/**
 * ComparatorProjectAccess class
 */
class ComparatorProjectAccess extends BasicProjectAccess
{

    protected $accesses;
    protected $object;

    /**
     * @param ProjectAccessInterface[] $accesses
     * @param StateTender              $object
     */
    public function __construct($accesses, $object = null)
    {
        $this->accesses = $accesses;
        $this->object = $object;
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
    /**
     * @return bool
     */
    public function canSeeStateTender ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeStateTender()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeAllStateTender ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeAllStateTender()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canCreateStateTender ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canCreateStateTender()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canEditStateTender ()
    {
        if ($this->object) {
            if ($this->object->getIsClosed()) {
                return false;
            }
        }
        foreach ($this->accesses as $access) {
            if ($access->canEditStateTender()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canChangeParticipation ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canChangeParticipation()) {
                return true;
            }
        }

        return false;
    }
}
