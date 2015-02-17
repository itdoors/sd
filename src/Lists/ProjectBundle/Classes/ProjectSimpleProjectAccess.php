<?php

namespace Lists\ProjectBundle\Classes;

/**
 * ProjectSimpleProjectAccess class
 */
class ProjectSimpleProjectAccess extends BasicProjectAccess
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
}
