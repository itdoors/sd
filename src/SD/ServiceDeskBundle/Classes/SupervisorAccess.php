<?php

namespace SD\ServiceDeskBundle\Classes;


/**
 * SupervisorAccess class
 */
class SupervisorAccess extends BasicAccess
{

    protected $name = 'supervisor';
    /**
     * @return bool
     */
    public function canSeeFinances()
    {

        return true;
    }

    /**
     * @return bool
     */
    public function canSeeWorks()
    {

        return true;
    }


    /**
     * @return bool
     */
    public function canEditFinances()
    {

        return false;
    }

    /**
     * @return bool
     */
    public function canPostToClient()
    {

        return true;
    }
    /**
     * @return bool
     */
    public function canPost()
    {

        return true;
    }

}
