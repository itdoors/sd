<?php

namespace Lists\OrganizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coea
 */
class Coea
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $organizationId;

    /**
     * @var integer
     */
    private $scopeId;

    /**
     * @var \Lists\LookupBundle\Entity\Lookup
     */
    private $scope;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $organization;


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
     * Set organizationId
     *
     * @param integer $organizationId
     * 
     * @return Coea
     */
    public function setOrganizationId($organizationId)
    {
        $this->organizationId = $organizationId;

        return $this;
    }

    /**
     * Get organizationId
     *
     * @return integer 
     */
    public function getOrganizationId()
    {
        return $this->organizationId;
    }

    /**
     * Set scopeId
     *
     * @param integer $scopeId
     * 
     * @return Coea
     */
    public function setScopeId($scopeId)
    {
        $this->scopeId = $scopeId;

        return $this;
    }

    /**
     * Get scopeId
     *
     * @return integer 
     */
    public function getScopeId()
    {
        return $this->scopeId;
    }

    /**
     * Set scope
     *
     * @param \Lists\LookupBundle\Entity\Lookup $scope
     * 
     * @return Coea
     */
    public function setScope(\Lists\LookupBundle\Entity\Lookup $scope = null)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return \Lists\LookupBundle\Entity\Lookup 
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set organization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organization
     * 
     * @return Coea
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
     * @ORM\PrePersist
     */
    public function doOnPrePersist()
    {
        // Add your code here
    }
}
