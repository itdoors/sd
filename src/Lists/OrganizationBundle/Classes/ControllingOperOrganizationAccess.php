<?php

namespace Lists\OrganizationBundle\Classes;

/**
 * ControllingOperOrganizationAccess class
 */
class ControllingOperOrganizationAccess extends BasicOrganizationAccess
{
    /**
     * @return bool
     */
    public function canAddContacts ()
    {
        return true;
    }
}
