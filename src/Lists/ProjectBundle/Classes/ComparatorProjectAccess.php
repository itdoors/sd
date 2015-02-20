<?php

namespace Lists\ProjectBundle\Classes;

use Lists\ProjectBundle\Interfaces\ProjectAccessInterface;
use Lists\ProjectBundle\Entity\ProjectStateTender;

/**
 * ComparatorProjectAccess class
 */
class ComparatorProjectAccess extends BasicProjectAccess
{

    protected $accesses;
    protected $object;

    /**
     * @param ProjectAccessInterface[] $accesses
     * @param ProjectStateTender       $object
     */
    public function __construct($accesses, $object = null)
    {
        $this->accesses = $accesses;
        $this->object = $object;
    }
    /**
     * @return bool
     */
    public function canSee ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSee()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeAll ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeAll()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canCreate ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canCreate()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeReport ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeReport()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canEdit ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canEdit()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canChangeManager ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canChangeManager()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canAddMessage ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canAddMessage()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canChangeManagerProject ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canChangeManagerProject()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canExportToExelAll ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canExportToExelAll()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeProjectStateTender ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeProjectStateTender()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeAllProjectStateTender ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeAllProjectStateTender()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canCreateProjectStateTender ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canCreateProjectStateTender()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canEditProjectStateTender ()
    {
        if ($this->object) {
            if ($this->object->getIsClosed() || !$this->object->getStatusAccess()) {
                return false;
            }
        }
        foreach ($this->accesses as $access) {
            if ($access->canEditProjectStateTender()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeProjectSimple ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeProjectSimple()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeAllProjectSimple ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeAllProjectSimple()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canCreateProjectSimple ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canCreateProjectSimple()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canEditProjectSimple ()
    {
        if ($this->object) {
            if ($this->object->getIsClosed() || !$this->object->getStatusAccess()) {
                return false;
            }
        }
        foreach ($this->accesses as $access) {
            if ($access->canEditProjectSimple()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeProjectCommercialTender ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeProjectCommercialTender()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeAllProjectCommercialTender ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeAllProjectCommercialTender()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canCreateProjectCommercialTender ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canCreateProjectCommercialTender()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canEditProjectCommercialTender ()
    {
        if ($this->object) {
            if ($this->object->getIsClosed() || !$this->object->getStatusAccess()) {
                return false;
            }
        }
        foreach ($this->accesses as $access) {
            if ($access->canEditProjectCommercialTender()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeProjectElectronicTrading ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeProjectElectronicTrading()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canSeeAllProjectElectronicTrading ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canSeeAllProjectElectronicTrading()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canCreateProjectElectronicTrading ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canCreateProjectElectronicTrading()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canEditProjectElectronicTrading ()
    {
        if ($this->object) {
            if ($this->object->getIsClosed() || !$this->object->getStatusAccess()) {
                return false;
            }
        }
        foreach ($this->accesses as $access) {
            if ($access->canEditProjectElectronicTrading()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canChangeParticipation ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canChangeParticipation()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canConfirmProject ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canConfirmProject()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canCloseProject ()
    {
        foreach ($this->accesses as $access) {
            if ($access->canCloseProject()) {
                return true;
            }
        }

        return false;
    }
}
