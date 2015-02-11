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
    public function canSeeProjectStateTender ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeAllProjectStateTender ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canCreateProjectStateTender ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canEditProjectStateTender ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeProjectSimple ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeAllProjectSimple ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canCreateProjectSimple ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canEditProjectSimple ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeProjectCommercialTender ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeAllProjectCommercialTender ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canCreateProjectCommercialTender ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canEditProjectCommercialTender ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeProjectElectronicTrading ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeAllProjectElectronicTrading ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canCreateProjectElectronicTrading ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canEditProjectElectronicTrading ()
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
