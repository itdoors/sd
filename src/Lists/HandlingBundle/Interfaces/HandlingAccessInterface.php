<?php

namespace Lists\HandlingBundle\Interfaces;

/**
 * HandlingAccessInterface interface
 */
interface HandlingAccessInterface
{
    /**
     * @return bool
     */
    public function canSee();
    /**
     * @return bool
     */
    public function canEdit();
    /**
     * @return bool
     */
    public function canAdd();
    /**
     * @return bool
     */
    public function canAddManager();
    /**
     * @return bool
     */
    public function canSeeListMy();
    /**
     * @return bool
     */
    public function canSeeList();
    /**
     * @return bool
     */
    public function canExportToExelAll();
}
