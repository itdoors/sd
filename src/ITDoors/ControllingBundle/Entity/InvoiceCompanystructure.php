<?php

namespace ITDoors\ControllingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvoiceCompanystructure
 */
class InvoiceCompanystructure
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $invoiceId;

    /**
     * @var integer
     */
    private $companystructureId;

    /**
     * @var \ITDoors\ControllingBundle\Entity\Invoice
     */
    private $invoice;

    /**
     * @var \Lists\CompanystructureBundle\Entity\Companystructure
     */
    private $companystructure;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId ()
    {
        return $this->id;
    }
    /**
     * Set invoiceId
     *
     * @param integer $invoiceId
     * 
     * @return InvoiceCompanystructure
     */
    public function setInvoiceId ($invoiceId)
    {
        $this->invoiceId = $invoiceId;

        return $this;
    }
    /**
     * Get invoiceId
     *
     * @return integer 
     */
    public function getInvoiceId ()
    {
        return $this->invoiceId;
    }
    /**
     * Set companystructureId
     *
     * @param integer $companystructureId
     * 
     * @return InvoiceCompanystructure
     */
    public function setCompanystructureId ($companystructureId)
    {
        $this->companystructureId = $companystructureId;

        return $this;
    }
    /**
     * Get companystructureId
     *
     * @return integer 
     */
    public function getCompanystructureId ()
    {
        return $this->companystructureId;
    }
    /**
     * Set invoice
     *
     * @param \ITDoors\ControllingBundle\Entity\Invoice $invoice
     * 
     * @return InvoiceCompanystructure
     */
    public function setInvoice (\ITDoors\ControllingBundle\Entity\Invoice $invoice = null)
    {
        $this->invoice = $invoice;

        return $this;
    }
    /**
     * Get invoice
     *
     * @return \ITDoors\ControllingBundle\Entity\Invoice 
     */
    public function getInvoice ()
    {
        return $this->invoice;
    }
    /**
     * Set companystructure
     *
     * @param \Lists\CompanystructureBundle\Entity\Companystructure $companystructure
     * 
     * @return InvoiceCompanystructure
     */
    public function setCompanystructure (\Lists\CompanystructureBundle\Entity\Companystructure $companystructure = null)
    {
        $this->companystructure = $companystructure;

        return $this;
    }
    /**
     * Get companystructure
     *
     * @return \Lists\CompanystructureBundle\Entity\Companystructure 
     */
    public function getCompanystructure ()
    {
        return $this->companystructure;
    }
    /**
     * @ORM\PreUpdate
     */
    public function addHistory ()
    {
        $history = new \ITDoors\HistoryBundle\Entity\History();
        $history->setModelName('InvoiceCompanystructyre');
        $history->setModelId($this->id);
        $history->setAction('update');
    }
}
