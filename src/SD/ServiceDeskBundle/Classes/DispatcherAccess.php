<?php

namespace SD\ServiceDeskBundle\Classes;


/**
 * DispatcherAccess class
 */
class DispatcherAccess extends BasicAccess
{
    protected $name = 'dispatcher';

    /**
     * @return bool
     */
    public function createNewCorporateClaim()
    {

        return true;
    }

    /**
     * @return bool
     */
    public function createNewOnceClaim()
    {

        return true;
    }

    /**
     * @return bool
     */
    public function canCloseClaim()
    {

        return true;
    }

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
    public function canEditMainInfo()
    {

        return true;
    }

    /**
     * @return bool
     */
    public function canEditPerformers()
    {

        return true;
    }

    /**
     * @return bool
     */
    public function canEditFinances()
    {

        return true;
    }

    /**
     * @return bool
     */
    public function canEditWorks()
    {

        return true;
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
