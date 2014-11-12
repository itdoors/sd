<?php

namespace Lists\OrganizationBundle\Classes;

/**
 * ManagerOrganizationAccess class
 */
class ManagerOrganizationAccess extends BasicOrganizationAccess
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
    public function canAddHandling ()
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
}
