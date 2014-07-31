<?php

namespace Lists\OrganizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Kved
 */
class Kved
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var integer
     */
    private $parentId;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $kvedOrganizations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->kvedOrganizations = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set code
     *
     * @param string $code
     *
     * @return Kved
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Kved
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
     *
     * @return Kved
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
     * Set parentId
     *
     * @param integer $parentId
     *
     * @return Kved
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer 
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Add kvedOrganizations
     *
     * @param \Lists\OrganizationBundle\Entity\kvedOrganization $kvedOrganizations
     *
     * @return Kved
     */
    public function addKvedOrganization(\Lists\OrganizationBundle\Entity\kvedOrganization $kvedOrganizations)
    {
        $this->kvedOrganizations[] = $kvedOrganizations;

        return $this;
    }

    /**
     * Remove kvedOrganizations
     *
     * @param \Lists\OrganizationBundle\Entity\kvedOrganization $kvedOrganizations
     */
    public function removeKvedOrganization(\Lists\OrganizationBundle\Entity\kvedOrganization $kvedOrganizations)
    {
        $this->kvedOrganizations->removeElement($kvedOrganizations);
    }

    /**
     * Get kvedOrganizations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getKvedOrganizations()
    {
        return $this->kvedOrganizations;
    }

    /**
     * @ORM\PrePersist
     */
    public function doOnPrePersist()
    {
        // Add your code here
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $kvedOrganization;


    /**
     * Get kvedOrganization
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getKvedOrganization()
    {
        return $this->kvedOrganization;
    }

    /**
     * @return string
     */
    public function __toString()
    {

        return '('.$this->code.') '.$this->name;
    }
}