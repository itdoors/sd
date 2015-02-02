<?php

namespace Lists\HandlingBundle\Classes;

/**
 * GosTenderAdminHandlingAccess class
 */
class GosTenderAdminHandlingAccess extends BasicHandlingAccess
{
    /**
     * @return bool
     */
    public function canCreateGosTender ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canSeeGosTender ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canEditGosTender ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canChangeParticipationInGosTander ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canSeeListGosTender ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canSeeAllGosTender ()
    {
        return true;
    }
}
