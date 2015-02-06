<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClaimOnce
 */
class ClaimOnce extends Claim
{
    /**
     * @var \SD\ServiceDeskBundle\Entity\Individual
     */
    protected $targetIndividual;

    /**
     * Set targetIndividual
     *
     * @param \SD\ServiceDeskBundle\Entity\Individual $individual
     * 
     * @return ClaimOnce
     */
    public function setTargetIndividual(\SD\ServiceDeskBundle\Entity\Individual $individual = null)
    {
        $this->targetIndividual = $individual;

        return $this;
    }

    /**
     * Get targetIndividual
     *
     * @return \SD\ServiceDeskBundle\Entity\Individual 
     */
    public function getTargetIndividual()
    {
        return $this->targetIndividual;
    }
}
