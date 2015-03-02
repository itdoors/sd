<?php

namespace Lists\ProjectBundle\Interfaces;

/**
 * ProjectAccessInterface interface
 */
interface ProjectAccessInterface
{
    /**
     * @return bool
     */
    public function canSee();
    /**
     * @return bool
     */
    public function canSeeAll();
    /**
     * @return bool
     */
    public function canCreate();
    /**
     * @return bool
     */
    public function canEdit();
    /**
     * @return bool
     */
    public function canSeeReport();
    /**
     * @return bool
     */
    public function canChangeManager();
    /**
     * @return bool
     */
    public function canAddMessage();
    /**
     * @return bool
     */
    public function canChangeManagerProject();
    /**
     * @return bool
     */
    public function canExportToExelAll();
    /**
     * @return bool
     */
    public function canSeeProjectStateTender();
    /**
     * @return bool
     */
    public function canSeeAllProjectStateTender();
    /**
     * @return bool
     */
    public function canCreateProjectStateTender();
    /**
     * @return bool
     */
    public function canEditProjectStateTender();
    /**
     * @return bool
     */
    public function canSeeProjectSimple();
    /**
     * @return bool
     */
    public function canSeeAllProjectSimple();
    /**
     * @return bool
     */
    public function canCreateProjectSimple();
    /**
     * @return bool
     */
    public function canEditProjectSimple();
    /**
     * @return bool
     */
    public function canSeeProjectCommercialTender();
    /**
     * @return bool
     */
    public function canSeeAllProjectCommercialTender();
    /**
     * @return bool
     */
    public function canCreateProjectCommercialTender();
    /**
     * @return bool
     */
    public function canEditProjectCommercialTender();
    /**
     * @return bool
     */
    public function canSeeProjectElectronicTrading();
    /**
     * @return bool
     */
    public function canSeeAllProjectElectronicTrading();
    /**
     * @return bool
     */
    public function canCreateProjectElectronicTrading();
    /**
     * @return bool
     */
    public function canEditProjectElectronicTrading();
    /**
     * @return bool
     */
    public function canChangeParticipation();
    /**
     * @return bool
     */
    public function canConfirmProject();
    /**
     * @return bool
     */
    public function canCloseProject();
    /**
     * @return bool
     */
    public function canAddFiles();
}
