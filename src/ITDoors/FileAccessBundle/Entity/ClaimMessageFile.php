<?php

namespace ITDoors\FileAccessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClaimMessageFile
 *
 * @ORM\Entity
 */
class ClaimMessageFile extends File
{
    /**
     * @var \SD\ServiceDeskBundle\Entity\Claim
     *
     * @ORM\ManyToOne(targetEntity="SD\ServiceDeskBundle\Entity\ClaimMessage", inversedBy="files")
     * @ORM\JoinColumn(name="claim_message__id", referencedColumnName="id")
     */
    protected $claimMessage;

    /**
     * Set claimMessage
     *
     * @param \SD\ServiceDeskBundle\Entity\ClaimMessage $claimMessage
     * 
     * @return ClaimMessageFile
     */
    public function setClaimMessage(\SD\ServiceDeskBundle\Entity\ClaimMessage $claimMessage = null)
    {
        $this->claimMessage = $claimMessage;

        return $this;
    }

    /**
     * Get claimMessage
     *
     * @return \SD\ServiceDeskBundle\Entity\ClaimMessage 
     */
    public function getClaimMessage()
    {
        return $this->claimMessage;
    }
}
