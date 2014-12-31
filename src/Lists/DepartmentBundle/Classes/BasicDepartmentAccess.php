<?php

namespace Lists\DepartmentBundle\Classes;

use Lists\DepartmentBundle\Interfaces\DepartmentAccessInterface;

/**
 * BasicDepartmentAccess class
 */
class BasicDepartmentAccess implements DepartmentAccessInterface
{
    /**
     * @return bool
     */
    public function canUse ()
    {
        return false;
    }
}
