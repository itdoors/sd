<?php

namespace Lists\HandlingBundle\Classes;

/**
 * GosTenderDirectorHandlingAccess class
 */
class GosTenderDirectorHandlingAccess extends BasicHandlingAccess
{
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
