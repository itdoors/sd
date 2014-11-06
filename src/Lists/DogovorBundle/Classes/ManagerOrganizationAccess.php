<?php

namespace Lists\DogovorBundle\Classes;

/**
 * ManagerOrganizationAccess class
 */
class ManagerOrganizationAccess extends BasicDogovorAccess
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
}
