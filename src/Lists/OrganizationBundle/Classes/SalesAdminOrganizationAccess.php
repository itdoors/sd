<?php

namespace Lists\OrganizationBundle\Classes;

/**
 * SalesAdminOrganizationAccess class
 */
class SalesAdminOrganizationAccess extends BasicOrganizationAccess
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
    public function canSeeAll ()
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
    public function canAddOrganization ()
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
    public function canEditSelf ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function filterFormName ()
    {
        return 'organizationSalesAdminFilterForm';
    }
}
