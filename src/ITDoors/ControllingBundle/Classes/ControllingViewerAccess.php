<?php

namespace ITDoors\ControllingBundle\Classes;

/**
 * ControllingViewerAccess class
 */
class ControllingViewerAccess extends BasicControllingAccess
{
    /**
     * @return bool
     */
    public function canSeeAll ()
    {
        return true;
    }
}
