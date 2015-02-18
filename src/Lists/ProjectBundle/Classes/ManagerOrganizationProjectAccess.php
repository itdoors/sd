<?php

namespace Lists\ProjectBundle\Classes;

/**
 * ManagerOrganizationProjectAccess class
 */
class ManagerOrganizationProjectAccess extends BasicProjectAccess
{
    /**
     * @return bool
     */
    public function canConfirmProject ()
    {
        return true;
    }
}
