<?php

namespace ITDoors\ControllingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ITDoors\ControllingBundle\Entity\InvoiceCompanystructure;

/**
 * Invoice
 */
class Invoice
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $invoiceId;

    /**
     * @var float
     */
    private $sum;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var \DateTime
     */
    private $delayDate;

    /**
     * @var integer
     */
    private $delayDays;

    /**
     * @var string
     */
    private $delayDaysType;

    /**
     * @var \DateTime
     */
    private $dateEnd;

    /**
     * @var \DateTime
     */
    private $dateFact;

    /**
     * @var integer
     */
    private $dogovorId;

    /**
     * @var string
     */
    private $dogovorGuid;

    /**
     * @var string
     */
    private $dogovorName;

    /**
     * @var string
     */
    private $dogovorNumber;

    /**
     * @var \DateTime
     */
    private $dogovorDate;

    /**
     * @var string
     */
    private $customerName;

    /**
     * @var string
     */
    private $customerEdrpou;

    /**
     * @var string
     */
    private $performerName;

    /**
     * @var string
     */
    private $performerEdrpou;

    /**
     * @var boolean
     */
    private $court;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $messages;

    /**
     * @var \Lists\DogovorBundle\Entity\Dogovor
     */
    private $dogovor;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param string $invoiceId Description
     * 
     * @return Invoice
     */
    public function setInvoiceId($invoiceId)
    {
        $this->invoiceId = $invoiceId;

        return $this;
    }

    /**
     * Get invoiceId
     *
     * @return string 
     */
    public function getInvoiceId()
    {
        return $this->invoiceId;
    }

    /**
     * Set sum
     *
     * @param float $sum 
     * 
     * @return Invoice
     */
    public function setSum($sum)
    {
        $this->sum = $sum;

        return $this;
    }

    /**
     * Get sum
     *
     * @return float 
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * 
     * @return Invoice
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
     * Set delayDate
     *
     * @param \DateTime $delayDate
     * 
     * @return Invoice
     */
    public function setDelayDate($delayDate)
    {
        $this->delayDate = $delayDate;

        return $this;
    }

    /**
     * Get delayDate
     *
     * @return \DateTime 
     */
    public function getDelayDate()
    {
        return $this->delayDate;
    }

    /**
     * Set delayDays
     *
     * @param integer $delayDays
     * 
     * @return Invoice
     */
    public function setDelayDays($delayDays)
    {
        $this->delayDays = $delayDays;

        return $this;
    }

    /**
     * Get delayDays
     *
     * @return integer 
     */
    public function getDelayDays()
    {
        return $this->delayDays;
    }

    /**
     * Set delayDaysType
     *
     * @param string $delayDaysType
     * 
     * @return Invoice
     */
    public function setDelayDaysType($delayDaysType)
    {
        $this->delayDaysType = $delayDaysType;

        return $this;
    }

    /**
     * Get delayDaysType
     *
     * @return string 
     */
    public function getDelayDaysType()
    {
        return $this->delayDaysType;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     * 
     * @return Invoice
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime 
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set dateFact
     *
     * @param \DateTime $dateFact
     * 
     * @return Invoice
     */
    public function setDateFact($dateFact)
    {
        $this->dateFact = $dateFact;

        return $this;
    }

    /**
     * Get dateFact
     *
     * @return \DateTime 
     */
    public function getDateFact()
    {
        return $this->dateFact;
    }

    /**
     * Set dogovorId
     *
     * @param integer $dogovorId
     * 
     * @return Invoice
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
     * Set dogovorGuid
     *
     * @param string $dogovorGuid
     * 
     * @return Invoice
     */
    public function setDogovorGuid($dogovorGuid)
    {
        $this->dogovorGuid = $dogovorGuid;

        return $this;
    }

    /**
     * Get dogovorGuid
     *
     * @return string 
     */
    public function getDogovorGuid()
    {
        return $this->dogovorGuid;
    }

    /**
     * Set dogovorName
     *
     * @param string $dogovorName
     * 
     * @return Invoice
     */
    public function setDogovorName($dogovorName)
    {
        $this->dogovorName = $dogovorName;

        return $this;
    }

    /**
     * Get dogovorName
     *
     * @return string 
     */
    public function getDogovorName()
    {
        return $this->dogovorName;
    }

    /**
     * Set dogovorNumber
     *
     * @param string $dogovorNumber
     * 
     * @return Invoice
     */
    public function setDogovorNumber($dogovorNumber)
    {
        $this->dogovorNumber = $dogovorNumber;

        return $this;
    }

    /**
     * Get dogovorNumber
     *
     * @return string 
     */
    public function getDogovorNumber()
    {
        return $this->dogovorNumber;
    }

    /**
     * Set dogovorDate
     *
     * @param \DateTime $dogovorDate
     * 
     * @return Invoice
     */
    public function setDogovorDate($dogovorDate)
    {
        $this->dogovorDate = $dogovorDate;

        return $this;
    }

    /**
     * Get dogovorDate
     *
     * @return \DateTime 
     */
    public function getDogovorDate()
    {
        return $this->dogovorDate;
    }

    /**
     * Set customerName
     *
     * @param string $customerName
     * 
     * @return Invoice
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;

        return $this;
    }

    /**
     * Get customerName
     *
     * @return string 
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * Set customerEdrpou
     *
     * @param string $customerEdrpou
     * 
     * @return Invoice
     */
    public function setCustomerEdrpou($customerEdrpou)
    {
        $this->customerEdrpou = $customerEdrpou;

        return $this;
    }

    /**
     * Get customerEdrpou
     *
     * @return string 
     */
    public function getCustomerEdrpou()
    {
        return $this->customerEdrpou;
    }

    /**
     * Set performerName
     *
     * @param string $performerName
     * 
     * @return Invoice
     */
    public function setPerformerName($performerName)
    {
        $this->performerName = $performerName;

        return $this;
    }

    /**
     * Get performerName
     *
     * @return string 
     */
    public function getPerformerName()
    {
        return $this->performerName;
    }

    /**
     * Set performerEdrpou
     *
     * @param string $performerEdrpou
     * 
     * @return Invoice
     */
    public function setPerformerEdrpou($performerEdrpou)
    {
        $this->performerEdrpou = $performerEdrpou;

        return $this;
    }

    /**
     * Get performerEdrpou
     *
     * @return string 
     */
    public function getPerformerEdrpou()
    {
        return $this->performerEdrpou;
    }

    /**
     * Set court
     *
     * @param boolean $court
     * 
     * @return Invoice
     */
    public function setCourt($court)
    {
        $this->court = $court;

        return $this;
    }

    /**
     * Get court
     *
     * @return boolean 
     */
    public function getCourt()
    {
        return $this->court;
    }

    /**
     * Add messages
     *
     * @param \ITDoors\ControllingBundle\Entity\InvoiceMessage $messages
     * 
     * @return Invoice
     */
    public function addMessage(\ITDoors\ControllingBundle\Entity\InvoiceMessage $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \ITDoors\ControllingBundle\Entity\InvoiceMessage $messages
     */
    public function removeMessage(\ITDoors\ControllingBundle\Entity\InvoiceMessage $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set dogovor
     *
     * @param \Lists\DogovorBundle\Entity\Dogovor $dogovor
     * 
     * @return Invoice
     */
    public function setDogovor(\Lists\DogovorBundle\Entity\Dogovor $dogovor = null)
    {
        $this->dogovor = $dogovor;

        return $this;
    }

    /**
     * Get dogovor
     *
     * @return \Lists\DogovorBundle\Entity\Dogovor 
     */
    public function getDogovor()
    {
        return $this->dogovor;
    }

    /**
     * __toString()
     * 
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getInvoiceId();
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
     * @return Invoice
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

    /**
     * @var integer
     */
    private $customerId;

    /**
     * @var integer
     */
    private $performerId;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $customer;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $performer;


    /**
     * Set customerId
     *
     * @param integer $customerId
     * 
     * @return Invoice
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * Get customerId
     *
     * @return integer 
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * Set performerId
     *
     * @param integer $performerId
     * 
     * @return Invoice
     */
    public function setPerformerId($performerId)
    {
        $this->performerId = $performerId;

        return $this;
    }

    /**
     * Get performerId
     *
     * @return integer 
     */
    public function getPerformerId()
    {
        return $this->performerId;
    }

    /**
     * Set customer
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $customer
     * 
     * @return Invoice
     */
    public function setCustomer(\Lists\OrganizationBundle\Entity\Organization $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \Lists\OrganizationBundle\Entity\Organization 
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set performer
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $performer
     * 
     * @return Invoice
     */
    public function setPerformer(\Lists\OrganizationBundle\Entity\Organization $performer = null)
    {
        $this->performer = $performer;

        return $this;
    }

    /**
     * Get performer
     *
     * @return \Lists\OrganizationBundle\Entity\Organization 
     */
    public function getPerformer()
    {
        return $this->performer;
    }
    /**
     * @var string
     */
    private $bank;


    /**
     * Set bank
     *
     * @param string $bank
     * 
     * @return Invoice
     */
    public function setBank($bank)
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * Get bank
     *
     * @return string 
     */
    public function getBank()
    {
        return $this->bank;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $payments;


    /**
     * Add payments
     *
     * @param \ITDoors\ControllingBundle\Entity\InvoicePayments $payments
     * 
     * @return Invoice
     */
    public function addPayment(\ITDoors\ControllingBundle\Entity\InvoicePayments $payments)
    {
        $this->payments[] = $payments;

        return $this;
    }

    /**
     * Remove payments
     *
     * @param \ITDoors\ControllingBundle\Entity\InvoicePayments $payments
     */
    public function removePayment(\ITDoors\ControllingBundle\Entity\InvoicePayments $payments)
    {
        $this->payments->removeElement($payments);
    }

    /**
     * Get payments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPayments()
    {
        return $this->payments;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $acts;


    /**
     * Add acts
     *
     * @param \ITDoors\ControllingBundle\Entity\InvoiceAct $acts
     * @return Invoice
     */
    public function addAct(\ITDoors\ControllingBundle\Entity\InvoiceAct $acts)
    {
        $this->acts[] = $acts;
    
        return $this;
    }

    /**
     * Remove acts
     *
     * @param \ITDoors\ControllingBundle\Entity\InvoiceAct $acts
     */
    public function removeAct(\ITDoors\ControllingBundle\Entity\InvoiceAct $acts)
    {
        $this->acts->removeElement($acts);
    }

    /**
     * Get acts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActs()
    {
        return $this->acts;
    }
}