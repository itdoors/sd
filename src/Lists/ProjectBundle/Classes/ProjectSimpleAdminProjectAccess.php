<?php

namespace Lists\ProjectBundle\Classes;

/**
 * ProjectSimpleAdminProjectAccess class
 */
class ProjectSimpleAdminProjectAccess extends BasicProjectAccess
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
    public function canSeeReport ()
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
    public function canSeeProjectCommercialTender ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canSeeProjectElectronicTrading ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canEditProjectStateTender ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canCloseProject ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canChangeManagerProject ()
    {
        return true;
    }
}
