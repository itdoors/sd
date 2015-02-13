<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClaimPerformerRule
 *
 * @ORM\Table(name="sd_claim_performer_rule", options={"comment" = "Rule for performer to access claim features"})
 * @ORM\Entity()
 */
class ClaimPerformerRule
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="can_edit_finance_data", type="boolean")
     */
    protected $canEditFinanceData = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="can_post_to_clients", type="boolean")
     */
    protected $canPostToClients;

    /**
     * @var boolean
     *
     * @ORM\Column(name="claim_updated", type="boolean")
     */
    protected $claimUpdated;

    /**
     * @var \SD\BusinessRoleBundle\Entity\ClaimPerformer
     *
     * @ORM\ManyToOne(targetEntity="SD\BusinessRoleBundle\Entity\ClaimPerformer", fetch="EAGER")
     * @ORM\JoinColumn(name="performer_id", referencedColumnName="id")
     */
    protected $claimPerformer;

    /**
     * @var \SD\ServiceDeskBundle\Entity\Claim
     *
     * @ORM\ManyToOne(targetEntity="SD\ServiceDeskBundle\Entity\Claim", fetch="EAGER")
     * @ORM\JoinColumn(name="claim_id", referencedColumnName="id")
     */
    protected $claim;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set canEditFinanceData
     *
     * @param boolean $canEditFinanceData
     * 
     * @return ClaimPerformerRule
     */
    public function setCanEditFinanceData($canEditFinanceData)
    {
        $this->canEditFinanceData = $canEditFinanceData;

        return $this;
    }

    /**
     * Get canEditFinanceData
     *
     * @return boolean 
     */
    public function getCanEditFinanceData()
    {
        return $this->canEditFinanceData;
    }

    /**
     * Set canPostToClients
     *
     * @param boolean $canPostToClients
     * 
     * @return ClaimPerformerRule
     */
    public function setCanPostToClients($canPostToClients)
    {
        $this->canPostToClients = $canPostToClients;

        return $this;
    }

    /**
     * Get canPostToClients
     *
     * @return boolean 
     */
    public function getCanPostToClients()
    {
        return $this->canPostToClients;
    }

    /**
     * Set claimUpdated
     *
     * @param boolean $claimUpdated
     * 
     * @return ClaimPerformerRule
     */
    public function setClaimUpdated($claimUpdated)
    {
        $this->claimUpdated = $claimUpdated;

        return $this;
    }

    /**
     * Get claimUpdated
     *
     * @return boolean 
     */
    public function getClaimUpdated()
    {
        return $this->claimUpdated;
    }

    /**
     * Set claimPerformer
     *
     * @param \SD\BusinessRoleBundle\Entity\ClaimPerformer $claimPerformer
     * 
     * @return ClaimPerformerRule
     */
    public function setClaimPerformer(\SD\BusinessRoleBundle\Entity\ClaimPerformer $claimPerformer = null)
    {
        $this->claimPerformer = $claimPerformer;

        return $this;
    }

    /**
     * Get claimPerformer
     *
     * @return \SD\BusinessRoleBundle\Entity\ClaimPerformer 
     */
    public function getClaimPerformer()
    {
        return $this->claimPerformer;
    }

    /**
     * Set claim
     *
     * @param \SD\ServiceDeskBundle\Entity\Claim $claim
     * 
     * @return ClaimPerformerRule
     */
    public function setClaim(\SD\ServiceDeskBundle\Entity\Claim $claim = null)
    {
        $this->claim = $claim;

        return $this;
    }

    /**
     * Get claim
     *
     * @return \SD\ServiceDeskBundle\Entity\Claim 
     */
    public function getClaim()
    {
        return $this->claim;
    }
}
