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
     * @ORM\Column(name="city", type="text", nullable=true)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    protected $address;

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
     * Set city
     *
     * @param string $city
     *
     * @return ImmovableClaimTarget
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return ImmovableClaimTarget
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    public function __toString()
    {
        return $this->city . ', ' . $this->address . ' (' . $this->type . ')';
    }
}
