<?php

namespace Lists\DogovorBundle\Classes;

/**
 * ComparatorDogovorAccess class
 */
class ComparatorDogovorAccess extends BasicDogovorAccess
{

    protected $accesses;

    /**
     * @param \Lists\DogovorBundle\Interfaces\DogovorAccessInterface[]   $accesses
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
    public function canSeeDanger ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeDanger()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canProlongate ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canProlongate()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canAddDogovor ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canAddDogovor()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canAddDopDogovor ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canAddDopDogovor()) {
                return true;
            }
        }

        return false;
    }
}
