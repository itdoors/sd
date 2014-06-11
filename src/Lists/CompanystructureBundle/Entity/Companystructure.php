<?php

namespace Lists\CompanystructureBundle\Entity;

use ITDoors\ControllingBundle\Entity\InvoiceCompanystructure;
use ITDoors\ControllingBundle\Entity\InvoiceCompanystructure;

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
     * @var \SD\UserBundle\Entity\Staff
     */
    private $staff;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $region;

    /**
     * Constructor
     */
    public function __construct()
    {
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
     *
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
     *
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
     *
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
     *
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
     *
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
     * Set staff
     *
     * @param \SD\UserBundle\Entity\Staff $staff
     *
     * @return Companystructure
     */
    public function setStaff(\SD\UserBundle\Entity\Staff $staff = null)
    {
        $this->staff = $staff;

        return $this;
    }

    /**
     * Get staff
     *
     * @return \SD\UserBundle\Entity\Staff
     */
    public function getStaff()
    {
        return $this->staff;
    }

    /**
     * Add region
     *
     * @param \Lists\RegionBundle\Entity\Region $region
     *
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

    /**
     *  __toString()
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @var integer
     */
    private $staffId;

    /**
     * Set staffId
     *
     * @param integer $staffId
     *
     * @return Companystructure
     */
    public function setStaffId($staffId)
    {
        $this->staffId = $staffId;

        return $this;
    }

    /**
     * Get staffId
     *
     * @return integer
     */
    public function getStaffId()
    {
        return $this->staffId;
    }

    /**
     * @var \Lists\CompanystructureBundle\Entity\Companystructure
     */
    private $parent;

    /**
     * Set parent
     *
     * @param \Lists\CompanystructureBundle\Entity\Companystructure $parent
     *
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $invoicedogovorcompany;

    /**
     * Add invoicedogovorcompany
     *
     * @param InvoiceCompanystructure $invoicedogovorcompany
     * 
     * @return Companystructure
     */
    public function addInvoicedogovorcompany(InvoiceCompanystructure $invoicedogovorcompany)
    {
        $this->invoicedogovorcompany[] = $invoicedogovorcompany;

        return $this;
    }

    /**
     * Remove invoicedogovorcompany
     *
     * @param InvoiceCompanystructure $invoicedogovorcompany
     */
    public function removeInvoicedogovorcompany(InvoiceCompanystructure $invoicedogovorcompany)
    {
        $this->invoicedogovorcompany->removeElement($invoicedogovorcompany);
    }

    /**
     * Get invoicedogovorcompany
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInvoicedogovorcompany()
    {
        return $this->invoicedogovorcompany;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $invoicecompanystructure;

    /**
     * Add invoicecompanystructure
     *
     * @param InvoiceCompanystructure $invoicecompanystructure
     * 
     * @return Companystructure
     */
    public function addInvoicecompanystructure(InvoiceCompanystructure $invoicecompanystructure)
    {
        $this->invoicecompanystructure[] = $invoicecompanystructure;

        return $this;
    }

    /**
     * Remove invoicecompanystructure
     *
     * @param InvoiceCompanystructure $invoicecompanystructure
     */
    public function removeInvoicecompanystructure(InvoiceCompanystructure $invoicecompanystructure)
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
}
