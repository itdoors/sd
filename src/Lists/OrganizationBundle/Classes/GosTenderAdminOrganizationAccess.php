<?php

namespace Lists\OrganizationBundle\Classes;

/**
 * GosTenderAdminOrganizationAccess class
 */
class GosTenderAdminOrganizationAccess extends BasicOrganizationAccess
{
    public function canAddOrganization ()
    {
        return true;
    }
}
