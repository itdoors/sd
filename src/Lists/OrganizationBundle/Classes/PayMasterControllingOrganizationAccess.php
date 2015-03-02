<?php

namespace Lists\OrganizationBundle\Classes;

/**
 * PayMasterControllingOrganizationAccess class
 */
class PayMasterControllingOrganizationAccess extends BasicOrganizationAccess
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
    public function canEdit ()
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
    public function canAddDocument ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canAddManager ()
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
    public function canAddKVED ()
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
    public function canAddBank ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canEditIsPayer ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canSeePayer ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canAddPayer ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canEditIsWithoutDogovor ()
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
