<?php

namespace ITDoors\ControllingBundle\Classes;

use ITDoors\ControllingBundle\Interfaces\ControllingAccessInterface;

/**
 * BasicControllingAccess class
 */
class BasicControllingAccess implements ControllingAccessInterface
{
    /**
     * @return bool
     */
    public function canEdit ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeAll ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canAddNote ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canAddResponsible ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canChangeStatusCourt ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canChangeStatus ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeExpectedData ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeExpectedPay ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeCustomersWithoutContacts ()
    {
        return false;
    }
}
