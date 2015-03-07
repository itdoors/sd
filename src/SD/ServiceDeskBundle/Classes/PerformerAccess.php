<?php

namespace SD\ServiceDeskBundle\Classes;


/**
 * PerformerAccess class
 */
class PerformerAccess extends BasicAccess
{
    protected $name = 'performer';
    private $canPostToClient;
    private $canEditFinanceData;

    public function __construct($canPostToClient, $canEditFinanceData) {
        $this->canEditFinanceData = $canEditFinanceData;
        $this->canPostToClient = $canPostToClient;
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
    public function canEditFinances()
    {

        return $this->canEditFinanceData;
    }

    /**
     * @return bool
     */
    public function canPostToClient()
    {

        return $this->canPostToClient;
    }
    /**
     * @return bool
     */
    public function canPost()
    {

        return true;
    }
}
