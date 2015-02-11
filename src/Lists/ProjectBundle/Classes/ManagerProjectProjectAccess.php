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
    public function canSeeProjectStateTender ()
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
    public function canEditProjectCommercialTender ()
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
    public function canChangeManager ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canEdit ()
    {
        return true;
    }
}
