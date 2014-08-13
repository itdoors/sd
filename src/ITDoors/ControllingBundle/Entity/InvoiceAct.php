<?php

namespace ITDoors\ControllingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvoiceAct
 */
class InvoiceAct
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
     * @var string
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var boolean
     */
    private $original;

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
     * @return InvoiceAct
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
     * Set name
     *
     * @param string $name
     * @return InvoiceAct
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
     * Set date
     *
     * @param \DateTime $date
     * @return InvoiceAct
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
     * Set original
     *
     * @param boolean $original
     * @return InvoiceAct
     */
    public function setOriginal($original)
    {
        $this->original = $original;
    
        return $this;
    }

    /**
     * Get original
     *
     * @return boolean 
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * Set invoice
     *
     * @param \ITDoors\ControllingBundle\Entity\Invoice $invoice
     * @return InvoiceAct
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
