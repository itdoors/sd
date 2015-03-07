<?php

namespace SD\ServiceDeskBundle\Classes;


/**
 * BasicAccess class
 */
class BasicAccess
{
    protected $name = 'basic';

    /**
     * @return bool
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function createNewCorporateClaim()
    {

        return false;
    }

    /**
     * @return bool
     */
    public function createNewOnceClaim()
    {

        return false;
    }

    /**
     * @return bool
     */
    public function canCloseClaim()
    {

        return false;
    }

    /**
     * @return bool
     */
    public function canSeeFinances()
    {

        return false || $this->canEditFinances();
    }

    /**
     * @return bool
     */
    public function canSeeWorks()
    {

        return false;
    }

    /**
     * @return bool
     */
    public function canEditMainInfo()
    {

        return false;
    }

    /**
     * @return bool
     */
    public function canEditPerformers()
    {

        return false;
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
    public function canEditWorks()
    {

        return false;
    }

    /**
     * @return bool
     */
    public function canPostToClient()
    {

        return false;
    }
    /**
     * @return bool
     */
    public function canPost()
    {

        return false;
    }

}
