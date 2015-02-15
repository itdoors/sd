<?php

namespace Lists\DepartmentBundle\Classes;

/**
 * HradminDepartmentAccess class
 */
class HradminDepartmentAccess extends BasicDepartmentAccess
{
    /**
     * @return bool
     */
    public function canUse ()
    {
        return true;
    }
}
