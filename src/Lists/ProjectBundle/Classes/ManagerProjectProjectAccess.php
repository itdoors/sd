<?php

namespace Lists\ProjectBundle\Classes;

/**
 * ManagerProjectProjectAccess class
 */
class ManagerProjectProjectAccess extends BasicProjectAccess
{
    /**
     * @return bool
     */
    public function canSeeStateTender ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canEditStateTender ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canChangeManager ()
    {
        return true;
    }
}
