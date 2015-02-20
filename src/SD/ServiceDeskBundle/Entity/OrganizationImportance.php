<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrganizationImportance
 * 
 * @ORM\Table(name="organization_importance")
 * @ORM\Entity
 */
class OrganizationImportance
{
    /**
     * @var bigint
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var SD\ServiceDeskBundle\Entity\ClaimImportance
     *
     * @ORM\ManyToOne(targetEntity="SD\ServiceDeskBundle\Entity\ClaimImportance")
     * @ORM\JoinColumn(name="importance_id", referencedColumnName="id")
     */
    protected $importance;

    /**
     * @var Lists\OrganizationBundle\Entity\Organization
     *
     * @ORM\ManyToOne(targetEntity="Lists\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id")
     */
    protected $organization;

    /**
     * @var bigint
     *
     * @ORM\Column(name="duration", type="bigint", nullable=true)
     */
    protected $duration;

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
     * Set duration
     *
     * @param integer $duration
     * 
     * @return OrganizationImportance
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set importance
     *
     * @param \SD\ServiceDeskBundle\Entity\ClaimImportance $importance
     * 
     * @return OrganizationImportance
     */
    public function setImportance(\SD\ServiceDeskBundle\Entity\ClaimImportance $importance = null)
    {
        $this->importance = $importance;

        return $this;
    }

    /**
     * Get importance
     *
     * @return \SD\ServiceDeskBundle\Entity\ClaimImportance 
     */
    public function getImportance()
    {
        return $this->importance;
    }

    /**
     * Set organization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organization
     * 
     * @return OrganizationImportance
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
}
