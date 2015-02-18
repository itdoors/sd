<?php

namespace Lists\ProjectBundle\Classes;

/**
 * ProjectStateTenderProjectAccess class
 */
class ProjectStateTenderProjectAccess extends BasicProjectAccess
{
    /**
     * @return bool
     */
    public function canCreateProjectStateTender ()
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
}
