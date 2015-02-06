<?php

namespace Lists\ProjectBundle\Classes;

/**
 * StateTenderProjectAccess class
 */
class StateTenderProjectAccess extends BasicProjectAccess
{
    /**
     * @return bool
     */
    public function canCreateStateTender ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canSeeStateTender ()
    {
        return true;
    }
}
