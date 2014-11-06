<?php

namespace Lists\OrganizationBundle\Classes;

/**
 * ControllingOrganizationAccess class
 */
class ControllingOrganizationAccess extends BasicOrganizationAccess
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
    public function canAddContacts ()
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
