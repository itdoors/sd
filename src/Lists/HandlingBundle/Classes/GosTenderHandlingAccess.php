<?php

namespace Lists\HandlingBundle\Classes;

/**
 * GosTenderHandlingAccess class
 */
class GosTenderHandlingAccess extends BasicHandlingAccess
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
    public function canSeeListGosTender ()
    {
        return true;
    }
}
