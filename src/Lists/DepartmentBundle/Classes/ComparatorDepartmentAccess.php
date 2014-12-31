<?php

namespace Lists\DepartmentBundle\Classes;

/**
 * ComparatorDepartmentAccess class
 */
class ComparatorDepartmentAccess extends BasicDepartmentAccess
{

    protected $accesses;

    /**
     * @param \Lists\DepartmentBundle\Interfaces\DepartmentAccessInterface[]   $accesses
     */
    public function __construct($accesses)
    {
        $this->accesses = $accesses;
    }
    /**
     * @return bool
     */
    public function canUse ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canUse()) {
                return true;
            }
        }

        return false;
    }
}
