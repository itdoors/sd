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
    private $number;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var boolean
     */
    private $original;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $detals;

    /**
     * @var \ITDoors\ControllingBundle\Entity\Invoice
     */
    private $invoice;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->detals = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set invoiceId
     *
     * @param integer $invoiceId
     * 
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
     * Set number
     *
     * @param string $number
     * 
     * @return InvoiceAct
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * 
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
     * 
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
     * Add detals
     *
     * @param \ITDoors\ControllingBundle\Entity\InvoiceActDetal $detals
     * 
     * @return InvoiceAct
     */
    public function addDetal(\ITDoors\ControllingBundle\Entity\InvoiceActDetal $detals)
    {
        $this->detals[] = $detals;

        return $this;
    }

    /**
     * Remove detals
     *
     * @param \ITDoors\ControllingBundle\Entity\InvoiceActDetal $detals
     */
    public function removeDetal(\ITDoors\ControllingBundle\Entity\InvoiceActDetal $detals)
    {
        $this->detals->removeElement($detals);
    }

    /**
     * Get detals
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDetals()
    {
        return $this->detals;
    }

    /**
     * Set invoice
     *
     * @param \ITDoors\ControllingBundle\Entity\Invoice $invoice
     * 
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