<?php

namespace ITDoors\ControllingBundle\Classes;

/**
 * ComparatorControllingAccess class
 */
class ComparatorControllingAccess extends BasicControllingAccess
{

    protected $accesses;

    /**
     * @param \Lists\OrganizationBundle\Interfaces\OrganizationAccessInterface[]   $accesses
     */
    public function __construct($accesses)
    {
        $this->accesses = $accesses;
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
    public function canAddNote ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canAddNote()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canAddResponsible ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canAddResponsible()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canChangeStatusCourt ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canChangeStatusCourt()) {
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
    public function canSeeExpectedData ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeExpectedData()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeExpectedPay ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeExpectedPay()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeCustomersWithoutContacts ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeCustomersWithoutContacts()) {
                return true;
            }
        }

        return false;
    }
}
