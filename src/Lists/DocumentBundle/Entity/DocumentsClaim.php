<?php

namespace Lists\DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocumentsClaim
 */
class DocumentsClaim
{
    /**
     * @var integer
     */
    private $claimId;

    /**
     * @var \Lists\DocumentBundle\Entity\Documents
     */
    private $documents;


    /**
     * Set claimId
     *
     * @param integer $claimId
     * @return DocumentsClaim
     */
    public function setClaimId($claimId)
    {
        $this->claimId = $claimId;
    
        return $this;
    }

    /**
     * Get claimId
     *
     * @return integer 
     */
    public function getClaimId()
    {
        return $this->claimId;
    }

    /**
     * Set documents
     *
     * @param \Lists\DocumentBundle\Entity\Documents $documents
     * @return DocumentsClaim
     */
    public function setDocuments(\Lists\DocumentBundle\Entity\Documents $documents = null)
    {
        $this->documents = $documents;
    
        return $this;
    }

    /**
     * Get documents
     *
     * @return \Lists\DocumentBundle\Entity\Documents 
     */
    public function getDocuments()
    {
        return $this->documents;
    }
}
