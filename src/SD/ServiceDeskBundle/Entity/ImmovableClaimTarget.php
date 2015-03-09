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
     * @var string
     *
     * @ORM\Column(name="street", type="text", nullable=true)
     */
    protected $street;

    /**
     * @var string
     *
     * @ORM\Column(name="bld", type="text", nullable=true)
     */
    protected $bld;

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

    /**
     * Set street
     *
     * @param string $street
     *
     * @return ImmovableClaimTarget
     */
    public function setStreet($street)
    {
        $this->street = $street;
    
        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set bld
     *
     * @param string $bld
     *
     * @return ImmovableClaimTarget
     */
    public function setBld($bld)
    {
        $this->bld = $bld;
    
        return $this;
    }

    /**
     * Get bld
     *
     * @return string 
     */
    public function getBld()
    {
        return $this->bld;
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString()
    {
        return $this->bld . ', ' . $this->street;
    }
}
