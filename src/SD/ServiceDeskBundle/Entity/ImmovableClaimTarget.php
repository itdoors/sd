<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImmovableClaimTarget
 *
 * @ORM\Entity()
 */
class ImmovableClaimTarget extends ClaimTarget
{
    /**
     * @var ImmovableClaimTargetType
     *
     * @ORM\Column(name="type", type="immovableClaimTargetType")
     */
    protected $type;

    /**
     * Set type
     *
     * @param immovableClaimTargetType $type
     *
     * @return ImmovableClaimTarget
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return immovableClaimTargetType 
     */
    public function getType()
    {
        return $this->type;
    }
}