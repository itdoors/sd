<?php

namespace Lists\OrganizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrganizationServiceCover
 */
class OrganizationServiceCover
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $serviceId;

    /**
     * @var integer
     */
    private $organizationId;

    /**
     * @var boolean
     */
    private $isInterested;

    /**
     * @var boolean
     */
    private $isWorking;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var string
     */
    private $responsible;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $organization;

    /**
     * @var \Lists\HandlingBundle\Entity\HandlingService
     */
    private $service;


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
     * Set serviceId
     *
     * @param integer $serviceId
     * @return OrganizationServiceCover
     */
    public function setServiceId($serviceId)
    {
        $this->serviceId = $serviceId;
    
        return $this;
    }

    /**
     * Get serviceId
     *
     * @return integer 
     */
    public function getServiceId()
    {
        return $this->serviceId;
    }

    /**
     * Set organizationId
     *
     * @param integer $organizationId
     * @return OrganizationServiceCover
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
     * Set isInterested
     *
     * @param boolean $isInterested
     * @return OrganizationServiceCover
     */
    public function setIsInterested($isInterested)
    {
        $this->isInterested = $isInterested;
    
        return $this;
    }

    /**
     * Get isInterested
     *
     * @return boolean 
     */
    public function getIsInterested()
    {
        return $this->isInterested;
    }

    /**
     * Set isWorking
     *
     * @param boolean $isWorking
     * @return OrganizationServiceCover
     */
    public function setIsWorking($isWorking)
    {
        $this->isWorking = $isWorking;
    
        return $this;
    }

    /**
     * Get isWorking
     *
     * @return boolean 
     */
    public function getIsWorking()
    {
        return $this->isWorking;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return OrganizationServiceCover
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    
        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set responsible
     *
     * @param string $responsible
     * @return OrganizationServiceCover
     */
    public function setResponsible($responsible)
    {
        $this->responsible = $responsible;
    
        return $this;
    }

    /**
     * Get responsible
     *
     * @return string 
     */
    public function getResponsible()
    {
        return $this->responsible;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return OrganizationServiceCover
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set organization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organization
     * @return OrganizationServiceCover
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
     * Set service
     *
     * @param \Lists\HandlingBundle\Entity\HandlingService $service
     *
     * @return OrganizationServiceCover
     */
    public function setService(\Lists\HandlingBundle\Entity\HandlingService $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Lists\HandlingBundle\Entity\HandlingService 
     */
    public function getService()
    {
        return $this->service;
    }
    /**
     * @var integer
     */
    private $evaluation;


    /**
     * Set evaluation
     *
     * @param integer $evaluation
     *
     * @return OrganizationServiceCover
     */
    public function setEvaluation($evaluation)
    {
        $this->evaluation = $evaluation;

        return $this;
    }

    /**
     * Get evaluation
     *
     * @return integer 
     */
    public function getEvaluation()
    {
        return $this->evaluation;
    }
    /**
     * @var integer
     */
    private $competitorId;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $competitor;


    /**
     * Set competitorId
     *
     * @param integer $competitorId
     *
     * @return OrganizationServiceCover
     */
    public function setCompetitorId($competitorId)
    {
        $this->competitorId = $competitorId;

        return $this;
    }

    /**
     * Get competitorId
     *
     * @return integer 
     */
    public function getCompetitorId()
    {
        return $this->competitorId;
    }

    /**
     * Set competitor
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $competitor
     * @return OrganizationServiceCover
     */
    public function setCompetitor(\Lists\OrganizationBundle\Entity\Organization $competitor = null)
    {
        $this->competitor = $competitor;
    
        return $this;
    }

    /**
     * Get competitor
     *
     * @return \Lists\OrganizationBundle\Entity\Organization 
     */
    public function getCompetitor()
    {
        return $this->competitor;
    }
    /**
     * @var integer
     */
    private $creatorId;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $creator;


    /**
     * Set creatorId
     *
     * @param integer $creatorId
     *
     * @return OrganizationServiceCover
     */
    public function setCreatorId($creatorId)
    {
        $this->creatorId = $creatorId;
    
        return $this;
    }

    /**
     * Get creatorId
     *
     * @return integer 
     */
    public function getCreatorId()
    {
        return $this->creatorId;
    }

    /**
     * Set creator
     *
     * @param \SD\UserBundle\Entity\User $creator
     *
     * @return OrganizationServiceCover
     */
    public function setCreator(\SD\UserBundle\Entity\User $creator = null)
    {
        $this->creator = $creator;
    
        return $this;
    }

    /**
     * Get creator
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getCreator()
    {
        return $this->creator;
    }
}