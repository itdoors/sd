<?php

namespace Lists\OrganizationBundle\Interfaces;

/**
 * OrganizationAccessInterface interface
 */
interface OrganizationAccessInterface
{
    /**
     * @return bool
     */
    public function canSee();
    /**
     * @return bool
     */
    public function canSeeAll();
    /**
     * @return bool
     */
    public function canEdit();
    /**
     * @return bool
     */
    public function canAddContacts();
    /**
     * @return bool
     */
    public function canAddDocument();
    /**
     * @return bool
     */
    public function canAddKVED();
    /**
     * @return bool
     */
    public function canAddManagerOrganization();
    /**
     * @return bool
     */
    public function canAddOrganization();
    /**
     * @return bool
     */
    public function canAddHandling();
    /**
     * @return bool
     */
    public function canExportToExcel();
    /**
     * @return bool
     */
    public function filterFormName();
}
