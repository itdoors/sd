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
    public function canSeeReport();
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
    /**
     * @return bool
     */
    public function canExportReportToExcel();
    /**
     * @return bool
     */
    public function canCreateGosTender();
    /**
     * @return bool
     */
    public function canSeeAllGosTender();
    /**
     * @return bool
     */
    public function canSeeGosTender();
    /**
     * @return bool
     */
    public function canSeeListGosTender();
    /**
     * @return bool
     */
    public function canEditGosTender();
    /**
     * @return bool
     */
    public function canChangeParticipationInGosTander();
}
