<?php

namespace Lists\ProjectBundle\Classes;

/**
 * ProjectStateTenderAdminProjectAccess class
 */
class ProjectStateTenderAdminProjectAccess extends BasicProjectAccess
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
    public function canSeeProjectStateTender ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canSeeAllProjectStateTender ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canCreateProjectStateTender ()
    {
        return true;
    }
}
