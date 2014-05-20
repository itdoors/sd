<?php

namespace ITDoors\ControllingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var integer
     */
    private $dogovorUUIE;

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
    private $dogovorActName;

    /**
     * @var \DateTime
     */
    private $dogovorActDate;

    /**
     * @var boolean
     */
    private $dogovorActOriginal;

    /**
     * @var string
     */
    private $organizationName;

    /**
     * @var string
     */
    private $organizationEdrpou;

    /**
     * @var string
     */
    private $organizationEdrpouDoer;

    /**
     * @var boolean
     */
    private $court;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $messages;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $organization;

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
     * @param string $invoiceId
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
     * Set dogovorUUIE
     *
     * @param integer $dogovorUUIE
     * 
     * @return Invoice
     */
    public function setDogovorUUIE($dogovorUUIE)
    {
        $this->dogovorUUIE = $dogovorUUIE;

        return $this;
    }

    /**
     * Get dogovorUUIE
     *
     * @return integer 
     */
    public function getDogovorUUIE()
    {
        return $this->dogovorUUIE;
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
     * Set dogovorActName
     *
     * @param string $dogovorActName
     * 
     * @return Invoice
     */
    public function setDogovorActName($dogovorActName)
    {
        $this->dogovorActName = $dogovorActName;

        return $this;
    }

    /**
     * Get dogovorActName
     *
     * @return string 
     */
    public function getDogovorActName()
    {
        return $this->dogovorActName;
    }

    /**
     * Set dogovorActDate
     *
     * @param \DateTime $dogovorActDate
     * 
     * @return Invoice
     */
    public function setDogovorActDate($dogovorActDate)
    {
        $this->dogovorActDate = $dogovorActDate;

        return $this;
    }

    /**
     * Get dogovorActDate
     *
     * @return \DateTime 
     */
    public function getDogovorActDate()
    {
        return $this->dogovorActDate;
    }

    /**
     * Set dogovorActOriginal
     *
     * @param boolean $dogovorActOriginal
     * 
     * @return Invoice
     */
    public function setDogovorActOriginal($dogovorActOriginal)
    {
        $this->dogovorActOriginal = $dogovorActOriginal;

        return $this;
    }

    /**
     * Get dogovorActOriginal
     *
     * @return boolean 
     */
    public function getDogovorActOriginal()
    {
        return $this->dogovorActOriginal;
    }

    /**
     * Set organizationName
     *
     * @param string $organizationName
     * 
     * @return Invoice
     */
    public function setOrganizationName($organizationName)
    {
        $this->organizationName = $organizationName;

        return $this;
    }

    /**
     * Get organizationName
     *
     * @return string 
     */
    public function getOrganizationName()
    {
        return $this->organizationName;
    }

    /**
     * Set organizationEdrpou
     *
     * @param string $organizationEdrpou
     * 
     * @return Invoice
     */
    public function setOrganizationEdrpou($organizationEdrpou)
    {
        $this->organizationEdrpou = $organizationEdrpou;

        return $this;
    }

    /**
     * Get organizationEdrpou
     *
     * @return string 
     */
    public function getOrganizationEdrpou()
    {
        return $this->organizationEdrpou;
    }

    /**
     * Set organizationEdrpouDoer
     *
     * @param string $organizationEdrpouDoer
     * 
     * @return Invoice
     */
    public function setOrganizationEdrpouDoer($organizationEdrpouDoer)
    {
        $this->organizationEdrpouDoer = $organizationEdrpouDoer;

        return $this;
    }

    /**
     * Get organizationEdrpouDoer
     *
     * @return string 
     */
    public function getOrganizationEdrpouDoer()
    {
        return $this->organizationEdrpouDoer;
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
     * Set organization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organization
     * 
     * @return Invoice
     */
    public function setOrganization(\Lists\OrganizationBundle\Entity\Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return \Lists\OrganizationBundle\Entity\Organization 
     */
    public function getOrganization()
    {
        return $this->organization;
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
     * @var string
     */
    private $dogovorId1c;


    /**
     * Set dogovorId1c
     *
     * @param string $dogovorId1c
     * @return Invoice
     */
    public function setDogovorId1c($dogovorId1c)
    {
        $this->dogovorId1c = $dogovorId1c;
    
        return $this;
    }

    /**
     * Get dogovorId1c
     *
     * @return string 
     */
    public function getDogovorId1c()
    {
        return $this->dogovorId1c;
    }
}
