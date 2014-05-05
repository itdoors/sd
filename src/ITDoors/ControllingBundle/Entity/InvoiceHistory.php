<?php

namespace ITDoors\ControllingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvoiceHistory
 */
class InvoiceHistory
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
    private $userId;

    /**
     * @var string
     */
    private $note;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var \ITDoors\ControllingBundle\Entity\Invoice
     */
    private $invoice;

    /**
     * @var \SD\UserBundle\Entity\user
     */
    private $user;


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
     * @return InvoiceHistory
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
     * Set userId
     *
     * @param integer $userId
     * 
     * @return InvoiceHistory
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set note
     *
     * @param string $note
     * 
     * @return InvoiceHistory
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * 
     * @return InvoiceHistory
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
     * 
     * @return InvoiceHistory
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
     * Set user
     *
     * @param \SD\UserBundle\Entity\user $user
     * 
     * @return InvoiceHistory
     */
    public function setUser(\SD\UserBundle\Entity\user $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \SD\UserBundle\Entity\user 
     */
    public function getUser()
    {
        return $this->user;
    }
}