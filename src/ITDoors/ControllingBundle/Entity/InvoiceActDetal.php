<?php

namespace ITDoors\ControllingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvoiceActDetal
 */
class InvoiceActDetal
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $invoiceActId;

    /**
     * @var string
     */
    private $mpk;

    /**
     * @var string
     */
    private $note;

    /**
     * @var float
     */
    private $count;

    /**
     * @var float
     */
    private $summa;

    /**
     * @var \ITDoors\ControllingBundle\Entity\InvoiceAct
     */
    private $act;


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
     * Set invoiceActId
     *
     * @param integer $invoiceActId
     * @return InvoiceActDetal
     */
    public function setInvoiceActId($invoiceActId)
    {
        $this->invoiceActId = $invoiceActId;
    
        return $this;
    }

    /**
     * Get invoiceActId
     *
     * @return integer 
     */
    public function getInvoiceActId()
    {
        return $this->invoiceActId;
    }

    /**
     * Set mpk
     *
     * @param string $mpk
     * @return InvoiceActDetal
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
     * Set note
     *
     * @param string $note
     * @return InvoiceActDetal
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
     * Set count
     *
     * @param float $count
     * @return InvoiceActDetal
     */
    public function setCount($count)
    {
        $this->count = $count;
    
        return $this;
    }

    /**
     * Get count
     *
     * @return float 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set summa
     *
     * @param float $summa
     * @return InvoiceActDetal
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
     * Set act
     *
     * @param \ITDoors\ControllingBundle\Entity\InvoiceAct $act
     * @return InvoiceActDetal
     */
    public function setAct(\ITDoors\ControllingBundle\Entity\InvoiceAct $act = null)
    {
        $this->act = $act;
    
        return $this;
    }

    /**
     * Get act
     *
     * @return \ITDoors\ControllingBundle\Entity\InvoiceAct 
     */
    public function getAct()
    {
        return $this->act;
    }
}