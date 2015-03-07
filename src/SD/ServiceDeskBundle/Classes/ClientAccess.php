<?php

namespace SD\ServiceDeskBundle\Classes;


/**
 * ClientAccess class
 */
class ClientAccess extends BasicAccess
{
    protected $name = 'client';
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
    public function canCloseClaim()
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
    public function canPostToClient()
    {

        return true;
    }

}
