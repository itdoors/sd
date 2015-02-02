<?php

namespace Lists\HandlingBundle\Classes;
use Lists\HandlingBundle\Entity\Handling;
use Lists\HandlingBundle\Interfaces\HandlingAccessInterface;

/**
 * ComparatorHandlingAccess class
 */
class ComparatorHandlingAccess extends BasicHandlingAccess
{

    protected $accesses;
    protected $handling;

    /**
     * @param HandlingAccessInterface[] $accesses
     * @param Handling                  $handling
     */
    public function __construct($accesses, Handling $handling = null)
    {
        $this->accesses = $accesses;
        $this->handling = $handling;
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
    /**
     * @return bool
     */
    public function canExportReportToExcel ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canExportReportToExcel()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canCreateGosTender ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canCreateGosTender()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeGosTender ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeGosTender()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canEditGosTender ()
    {
        if ($this->handling && $this->handling->getIsClosed()) {
            return false;
        }
        foreach ($this->accesses as $access) {
            if ($access->canEditGosTender()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canChangeParticipationInGosTander ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canChangeParticipationInGosTander()) {
                return true;
            }
        }

        return false;
    }
}
