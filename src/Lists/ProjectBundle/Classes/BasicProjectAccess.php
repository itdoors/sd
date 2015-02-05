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
}
