<?php

namespace Lists\ProjectBundle\Classes;

/**
 * StateTenderDirectorProjectAccess class
 */
class StateTenderDirectorProjectAccess extends BasicProjectAccess
{
    /**
     * @return bool
     */
    public function canChangeParticipation ()
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
