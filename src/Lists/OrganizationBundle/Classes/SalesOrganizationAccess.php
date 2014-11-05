<?php

namespace Lists\OrganizationBundle\Classes;

/**
 * SalesOrganizationAccess class
 */
class SalesOrganizationAccess extends BasicOrganizationAccess
{
    /**
     * @return bool
     */
    public function canSee ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canAddManagerOrganization ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canExportToExcel ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canAddOrganization ()
    {
        return true;
    }
}
