<?php

namespace Lists\DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocumentsOrganization
 */
class DocumentsOrganization
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $organization;

    /**
     * @var \Lists\DocumentBundle\Entity\Documents
     */
    private $documents;


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
     * Set organization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organization
     * @return DocumentsOrganization
     */
    public function setOrganization(\Lists\OrganizationBundle\Entity\Organization $organization = null)
    {
        $this->organization = $organization;
    
        return $this;
    }

    /**
     * Get organization
     *
     * @return \Lists\OrganizationBundle\Entity\Organization 
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Set documents
     *
     * @param \Lists\DocumentBundle\Entity\Documents $documents
     * @return DocumentsOrganization
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
    /**
     * @ORM\PrePersist
     */
    public function doOnPrePersist()
    {
        // Add your code here
    }
}
