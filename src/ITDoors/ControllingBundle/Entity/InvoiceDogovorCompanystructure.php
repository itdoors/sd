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
}
