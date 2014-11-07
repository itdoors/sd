<?php

namespace Lists\HandlingBundle\Classes;

/**
 * ManagerOrganizationAccess class
 */
class ManagerOrganizationAccess extends BasicHandlingAccess
{
    /**
     * @return bool
     */
    public function canAdd ()
    {
        return true;
    }
}
