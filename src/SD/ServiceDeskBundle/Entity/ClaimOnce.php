<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClaimOnce
 *
 * @ORM\Entity(repositoryClass="ClaimOnceRepository")
 */
class ClaimOnce extends Claim
{
    /**
     * @var \SD\ServiceDeskBundle\Entity\ClaimTarget
     *
     * @ORM\ManyToOne(targetEntity="SD\ServiceDeskBundle\Entity\ClaimTarget")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="claim_target_id", referencedColumnName="id")
     * })
     */
    protected $claimTarget;

    /**
     * Set claimTarget
     *
     * @param \SD\ServiceDeskBundle\Entity\ClaimTarget $claimTarget
     *
     * @return ClaimOnce
     */
    public function setClaimTarget(\SD\ServiceDeskBundle\Entity\ClaimTarget $claimTarget = null)
    {
        $this->claimTarget = $claimTarget;
    
        return $this;
    }

    /**
     * Get claimTarget
     *
     * @return \SD\ServiceDeskBundle\Entity\ClaimTarget 
     */
    public function getClaimTarget()
    {
        return $this->claimTarget;
    }

    public function __toString () {
        return (string) $this->getId();
    }
}
