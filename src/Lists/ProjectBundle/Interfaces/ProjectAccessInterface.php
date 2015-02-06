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
    public function canChangeManager();
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
    public function canSeeStateTender();
    /**
     * @return bool
     */
    public function canSeeAllStateTender();
    /**
     * @return bool
     */
    public function canCreateStateTender();
    /**
     * @return bool
     */
    public function canEditStateTender();
    /**
     * @return bool
     */
    public function canChangeParticipation();
}
