<?php

namespace Lists\ProjectBundle\Classes;

/**
 * ProjectSimpleDirectorProjectAccess class
 */
class ProjectSimpleDirectorProjectAccess extends BasicProjectAccess
{
    /**
     * @return bool
     */
    public function canCreateProjectSimple ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canSeeProjectSimple ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canEditProjectSimple ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canSeeAllProjectSimple ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canAddFiles ()
    {
        return true;
    }
}
