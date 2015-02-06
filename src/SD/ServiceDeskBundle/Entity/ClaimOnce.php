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
    protected $individual;

    /**
     * Set individual
     *
     * @param \SD\ServiceDeskBundle\Entity\Individual $individual
     * 
     * @return ClaimOnce
     */
    public function setIndividual(\SD\ServiceDeskBundle\Entity\Individual $individual = null)
    {
        $this->individual = $individual;

        return $this;
    }

    /**
     * Get individual
     *
     * @return \SD\ServiceDeskBundle\Entity\Individual 
     */
    public function getIndividual()
    {
        return $this->individual;
    }
}
