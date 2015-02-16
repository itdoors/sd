<?php

namespace Lists\OrganizationBundle\Classes;

/**
 * ComparatorOrganizationAccess class
 */
class ComparatorOrganizationAccess extends BasicOrganizationAccess
{

    protected $accesses;

    /**
     * @param \Lists\OrganizationBundle\Interfaces\OrganizationAccessInterface[]   $accesses
     */
    public function __construct($accesses)
    {
        $this->accesses = $accesses;
    }
    /**
     * @return bool
     */
    public function canSee ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSee()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeAll ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeAll()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canEdit ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canEdit()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canAddContacts ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canAddContacts()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canAddDocument ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canAddDocument()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canAddKVED ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canAddKVED()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canAddManagerOrganization ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canAddManagerOrganization()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canAddOrganization ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canAddOrganization()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canAddHandling ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canAddHandling()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canExportToExcel ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canExportToExcel()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canAddBank ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canAddBank()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canEditSelf ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canEditSelf()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canEditIsPayer ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canEditIsPayer()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function filterFormName ()
    {
        foreach ($this->accesses as $access) {
            if ($access->filterFormName() == 'organizationSalesAdminFilterForm') {
                return 'organizationSalesAdminFilterForm';
            }
        }

        return 'organizationSalesFilterForm';
    }
}
