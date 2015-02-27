<?php

namespace Lists\OrganizationBundle\Classes;

use Lists\OrganizationBundle\Interfaces\OrganizationAccessInterface;

/**
 * BasicOrganizationAccess class
 */
class BasicOrganizationAccess implements OrganizationAccessInterface
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
    public function canEdit ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canAddContacts ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canAddDocument ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canAddKVED ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canAddManagerOrganization ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canAddOrganization ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canAddHandling ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canExportToExcel ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canAddBank ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canEditSelf ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canEditIsPayer ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeePayer ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canAddPayer ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function filterFormName ()
    {
        return 'organizationSalesFilterForm';
    }
}
