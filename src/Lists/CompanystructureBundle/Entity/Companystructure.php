<?php

namespace Lists\CompanystructureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Companystructure
 */
class Companystructure
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $mpk;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var integer
     */
    private $stuffId;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $invoicecompanystructure;

    /**
     * @var \SD\UserBundle\Entity\Stuff
     */
    private $stuff;

    /**
     * @var \Lists\CompanystructureBundle\Entity\Companystructure
     */
    private $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $region;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->invoicecompanystructure = new \Doctrine\Common\Collections\ArrayCollection();
        $this->region = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Companystructure
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
     * Set mpk
     *
     * @param string $mpk
     * @return Companystructure
     */
    public function setMpk($mpk)
    {
        $this->mpk = $mpk;
    
        return $this;
    }

    /**
     * Get mpk
     *
     * @return string 
     */
    public function getMpk()
    {
        return $this->mpk;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Companystructure
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Companystructure
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set stuffId
     *
     * @param integer $stuffId
     * @return Companystructure
     */
    public function setStuffId($stuffId)
    {
        $this->stuffId = $stuffId;
    
        return $this;
    }

    /**
     * Get stuffId
     *
     * @return integer 
     */
    public function getStuffId()
    {
        return $this->stuffId;
    }

    /**
     * Add invoicecompanystructure
     *
     * @param \ITDoors\ControllingBundle\Entity\InvoiceCompanystructure $invoicecompanystructure
     * @return Companystructure
     */
    public function addInvoicecompanystructure(\ITDoors\ControllingBundle\Entity\InvoiceCompanystructure $invoicecompanystructure)
    {
        $this->invoicecompanystructure[] = $invoicecompanystructure;
    
        return $this;
    }

    /**
     * Remove invoicecompanystructure
     *
     * @param \ITDoors\ControllingBundle\Entity\InvoiceCompanystructure $invoicecompanystructure
     */
    public function removeInvoicecompanystructure(\ITDoors\ControllingBundle\Entity\InvoiceCompanystructure $invoicecompanystructure)
    {
        $this->invoicecompanystructure->removeElement($invoicecompanystructure);
    }

    /**
     * Get invoicecompanystructure
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInvoicecompanystructure()
    {
        return $this->invoicecompanystructure;
    }

    /**
     * Set stuff
     *
     * @param \SD\UserBundle\Entity\Stuff $stuff
     * @return Companystructure
     */
    public function setStuff(\SD\UserBundle\Entity\Stuff $stuff = null)
    {
        $this->stuff = $stuff;
    
        return $this;
    }

    /**
     * Get stuff
     *
     * @return \SD\UserBundle\Entity\Stuff 
     */
    public function getStuff()
    {
        return $this->stuff;
    }

    /**
     * Set parent
     *
     * @param \Lists\CompanystructureBundle\Entity\Companystructure $parent
     * @return Companystructure
     */
    public function setParent(\Lists\CompanystructureBundle\Entity\Companystructure $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \Lists\CompanystructureBundle\Entity\Companystructure 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add region
     *
     * @param \Lists\RegionBundle\Entity\Region $region
     * @return Companystructure
     */
    public function addRegion(\Lists\RegionBundle\Entity\Region $region)
    {
        $this->region[] = $region;
    
        return $this;
    }

    /**
     * Remove region
     *
     * @param \Lists\RegionBundle\Entity\Region $region
     */
    public function removeRegion(\Lists\RegionBundle\Entity\Region $region)
    {
        $this->region->removeElement($region);
    }

    /**
     * Get region
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRegion()
    {
        return $this->region;
    }
}
