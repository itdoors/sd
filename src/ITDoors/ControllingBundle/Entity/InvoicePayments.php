<?php

namespace ITDoors\ControllingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvoicePayments
 */
class InvoicePayments
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
     * @var float
     */
    private $summa;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var \ITDoors\ControllingBundle\Entity\Invoice
     */
    private $invoice;


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
     * Set invoiceId
     *
     * @param integer $invoiceId
     * @return InvoicePayments
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
     * Set summa
     *
     * @param float $summa
     * @return InvoicePayments
     */
    public function setSumma($summa)
    {
        $this->summa = $summa;
    
        return $this;
    }

    /**
     * Get summa
     *
     * @return float 
     */
    public function getSumma()
    {
        return $this->summa;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return InvoicePayments
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set invoice
     *
     * @param \ITDoors\ControllingBundle\Entity\Invoice $invoice
     * @return InvoicePayments
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
}
