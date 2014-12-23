<?php

namespace ITDoors\PayMasterBundle\Interfaces;

/**
 * PayMasterAccessInterface interface
 */
interface PayMasterAccessInterface
{
    /**
     * @return bool
     */
    public function canAdd();
    /**
     * @return bool
     */
    public function canSeeAll();
    /**
     * @return bool
     */
    public function canRemove();
    /**
     * @return bool
     */
    public function canChangeStatus();
    /**
     * @return bool
     */
    public function canChangeIsAcceptance();
    /**
     * @return bool
     */
    public function canChangeToPay();
}
