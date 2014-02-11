<?php

namespace Lists\OrganizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrganizationGroup
 */
class OrganizationGroup
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;


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
     * Set slug
     *
     * @param string $slug
     * @return OrganizationGroup
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return OrganizationGroup
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return OrganizationGroup
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

    public function __toString()
    {
        return $this->getName();
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $organizations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->organizations = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add organizations
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organizations
     * @return OrganizationGroup
     */
    public function addOrganization(\Lists\OrganizationBundle\Entity\Organization $organizations)
    {
        $this->organizations[] = $organizations;
    
        return $this;
    }

    /**
     * Remove organizations
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organizations
     */
    public function removeOrganization(\Lists\OrganizationBundle\Entity\Organization $organizations)
    {
        $this->organizations->removeElement($organizations);
    }

    /**
     * Get organizations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrganizations()
    {
        return $this->organizations;
    }
}