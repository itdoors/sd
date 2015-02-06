<?php

namespace Lists\ProjectBundle\Classes;

use Lists\ProjectBundle\Interfaces\ProjectAccessInterface;

/**
 * BasicProjectAccess class
 */
class BasicProjectAccess implements ProjectAccessInterface
{
    /**
     * @return bool
     */
    public function canSee ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeAll ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canCreate ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canEdit ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canChangeManager ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canChangeManagerProject ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canExportToExelAll ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeStateTender ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeAllStateTender ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canCreateStateTender ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canEditStateTender ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canChangeParticipation ()
    {
        return false;
    }
}
