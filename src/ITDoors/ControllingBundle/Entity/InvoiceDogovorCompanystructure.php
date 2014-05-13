<?php

namespace ITDoors\ControllingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvoiceDogovorCompanystructure
 */
class InvoiceDogovorCompanystructure
{
    /**
     * @var integer
     */
    private $invoiceId;

    /**
     * @var integer
     */
    private $dogovorId;

    /**
     * @var integer
     */
    private $companystructureId;


    /**
     * Set invoiceId
     *
     * @param integer $invoiceId
     * 
     * @return InvoiceDogovorCompanystructure
     */
    public function setInvoiceId($invoiceId)
    {
        $this->invoiceId = $invoiceId;

        return $this;
    }

    /**
     * Get invoiceId
     *
     * @return integer 
     */
    public function getInvoiceId()
    {
        return $this->invoiceId;
    }

    /**
     * Set dogovorId
     *
     * @param integer $dogovorId
     * 
     * @return InvoiceDogovorCompanystructure
     */
    public function setDogovorId($dogovorId)
    {
        $this->dogovorId = $dogovorId;

        return $this;
    }

    /**
     * Get dogovorId
     *
     * @return integer 
     */
    public function getDogovorId()
    {
        return $this->dogovorId;
    }

    /**
     * Set companystructureId
     *
     * @param integer $companystructureId
     * 
     * @return InvoiceDogovorCompanystructure
     */
    public function setCompanystructureId($companystructureId)
    {
        $this->companystructureId = $companystructureId;

        return $this;
    }

    /**
     * Get companystructureId
     *
     * @return integer 
     */
    public function getCompanystructureId()
    {
        return $this->companystructureId;
    }
    /**
     * @var \ITDoors\ControllingBundle\Entity\Invoice
     */
    private $invoice;


    /**
     * Set invoice
     *
     * @param \ITDoors\ControllingBundle\Entity\Invoice $invoice
     * 
     * @return InvoiceDogovorCompanystructure
     */
    public function setInvoice(\ITDoors\ControllingBundle\Entity\Invoice $invoice = null)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @return \ITDoors\ControllingBundle\Entity\Invoice 
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * __toString()
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->invoice();
    }
    /**
     * @var \Lists\CompanystructureBundle\Entity\Companystructure
     */
    private $company;


    /**
     * Set company
     *
     * @param \Lists\CompanystructureBundle\Entity\Companystructure $company
     * 
     * @return InvoiceDogovorCompanystructure
     */
    public function setCompany(\Lists\CompanystructureBundle\Entity\Companystructure $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \Lists\CompanystructureBundle\Entity\Companystructure 
     */
    public function getCompany()
    {
        return $this->company;
    }
}