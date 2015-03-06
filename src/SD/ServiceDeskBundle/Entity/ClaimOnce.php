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
     * @var \Lists\IndividualBundle\Entity\Individual
     *
     * @ORM\ManyToOne(targetEntity="Lists\IndividualBundle\Entity\Individual")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="targetIndividual_id", referencedColumnName="id")
     * })
     */
    private $targetIndividual;

    /**
     * Set targetIndividual
     *
     * @param \Lists\IndividualBundle\Entity\Individual $targetIndividual
     * 
     * @return ClaimOnce
     */
    public function setTargetIndividual(\Lists\IndividualBundle\Entity\Individual $targetIndividual = null)
    {
        $this->targetIndividual = $targetIndividual;

        return $this;
    }

    /**
     * Get targetIndividual
     *
     * @return \Lists\IndividualBundle\Entity\Individual 
     */
    public function getTargetIndividual()
    {
        return $this->targetIndividual;
    }
}
