<?php

namespace SD\DashboardBundle\Classes;

/**
 * ComparatorDashboardAccess class
 */
class ComparatorDashboardAccess extends BasicDashboardAccess
{

    protected $accesses;

    /**
     * @param \SD\DashboardBundle\Interfaces\DashboardAccessInterface[]   $accesses
     */
    public function __construct($accesses)
    {
        $this->accesses = $accesses;
    }
    /**
     * @return bool
     */
    public function canSeeMyActions ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeMyActions()) {
                return true;
            }
        }

        return false;
    }
}
