<?php

namespace Lists\HandlingBundle\Classes;

/**
 * TenderAdminHandlingAccess class
 */
class TenderAdminHandlingAccess extends BasicHandlingAccess
{
    /**
     * @return bool
     */
    public function canEdit ()
    {
        return true;
    }
}
