<?php

namespace ITDoors\ControllingBundle\Interfaces;

/**
 * ControllingAccessInterface interface
 */
interface ControllingAccessInterface
{
    /**
     * @return bool
     */
    public function canEdit();
    /**
     * @return bool
     */
    public function canSeeAll();
    /**
     * @return bool
     */
    public function canAddNote();
    /**
     * @return bool
     */
    public function canAddResponsible();
    /**
     * @return bool
     */
    public function canChangeStatusCourt();
    /**
     * @return bool
     */
    public function canChangeStatus();
}
