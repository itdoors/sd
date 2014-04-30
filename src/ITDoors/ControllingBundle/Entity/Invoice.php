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
    private $invoice_id;

    /**
     * @var float
     */
    private $sum;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var boolean
     */
    private $postponement;

    /**
     * @var \DateTime
     */
    private $date_end;

    /**
     * @var \DateTime
     */
    private $date_fact;

    /**
     * @var string
     */
    private $dogovor_name;

    /**
     * @var \DateTime
     */
    private $dogovor_date;

    /**
     * @var string
     */
    private $dogovor_act_name;

    /**
     * @var \DateTime
     */
    private $dogovor_act_date;

    /**
     * @var boolean
     */
    private $dogovor_act_original;

    /**
     * @var string
     */
    private $city_name;

    /**
     * @var string
     */
    private $organization_name;

    /**
     * @var string
     */
    private $organization_edrpou;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $organization;

    /**
     * @var \Lists\DogovorBundle\Entity\Dogovor
     */
    private $dogovor;


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
     * Set invoice_id
     *
     * @param string $invoiceId
     * @return Invoice
     */
    public function setInvoiceId($invoiceId)
    {
        $this->invoice_id = $invoiceId;
    
        return $this;
    }

    /**
     * Get invoice_id
     *
     * @return string 
     */
    public function getInvoiceId()
    {
        return $this->invoice_id;
    }

    /**
     * Set sum
     *
     * @param float $sum
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
     * Set postponement
     *
     * @param boolean $postponement
     * @return Invoice
     */
    public function setPostponement($postponement)
    {
        $this->postponement = $postponement;
    
        return $this;
    }

    /**
     * Get postponement
     *
     * @return boolean 
     */
    public function getPostponement()
    {
        return $this->postponement;
    }

    /**
     * Set date_end
     *
     * @param \DateTime $dateEnd
     * @return Invoice
     */
    public function setDateEnd($dateEnd)
    {
        $this->date_end = $dateEnd;
    
        return $this;
    }

    /**
     * Get date_end
     *
     * @return \DateTime 
     */
    public function getDateEnd()
    {
        return $this->date_end;
    }

    /**
     * Set date_fact
     *
     * @param \DateTime $dateFact
     * @return Invoice
     */
    public function setDateFact($dateFact)
    {
        $this->date_fact = $dateFact;
    
        return $this;
    }

    /**
     * Get date_fact
     *
     * @return \DateTime 
     */
    public function getDateFact()
    {
        return $this->date_fact;
    }

    /**
     * Set dogovor_name
     *
     * @param string $dogovorName
     * @return Invoice
     */
    public function setDogovorName($dogovorName)
    {
        $this->dogovor_name = $dogovorName;
    
        return $this;
    }

    /**
     * Get dogovor_name
     *
     * @return string 
     */
    public function getDogovorName()
    {
        return $this->dogovor_name;
    }

    /**
     * Set dogovor_date
     *
     * @param \DateTime $dogovorDate
     * @return Invoice
     */
    public function setDogovorDate($dogovorDate)
    {
        $this->dogovor_date = $dogovorDate;
    
        return $this;
    }

    /**
     * Get dogovor_date
     *
     * @return \DateTime 
     */
    public function getDogovorDate()
    {
        return $this->dogovor_date;
    }

    /**
     * Set dogovor_act_name
     *
     * @param string $dogovorActName
     * @return Invoice
     */
    public function setDogovorActName($dogovorActName)
    {
        $this->dogovor_act_name = $dogovorActName;
    
        return $this;
    }

    /**
     * Get dogovor_act_name
     *
     * @return string 
     */
    public function getDogovorActName()
    {
        return $this->dogovor_act_name;
    }

    /**
     * Set dogovor_act_date
     *
     * @param \DateTime $dogovorActDate
     * @return Invoice
     */
    public function setDogovorActDate($dogovorActDate)
    {
        $this->dogovor_act_date = $dogovorActDate;
    
        return $this;
    }

    /**
     * Get dogovor_act_date
     *
     * @return \DateTime 
     */
    public function getDogovorActDate()
    {
        return $this->dogovor_act_date;
    }

    /**
     * Set dogovor_act_original
     *
     * @param boolean $dogovorActOriginal
     * @return Invoice
     */
    public function setDogovorActOriginal($dogovorActOriginal)
    {
        $this->dogovor_act_original = $dogovorActOriginal;
    
        return $this;
    }

    /**
     * Get dogovor_act_original
     *
     * @return boolean 
     */
    public function getDogovorActOriginal()
    {
        return $this->dogovor_act_original;
    }

    /**
     * Set city_name
     *
     * @param string $cityName
     * @return Invoice
     */
    public function setCityName($cityName)
    {
        $this->city_name = $cityName;
    
        return $this;
    }

    /**
     * Get city_name
     *
     * @return string 
     */
    public function getCityName()
    {
        return $this->city_name;
    }

    /**
     * Set organization_name
     *
     * @param string $organizationName
     * @return Invoice
     */
    public function setOrganizationName($organizationName)
    {
        $this->organization_name = $organizationName;
    
        return $this;
    }

    /**
     * Get organization_name
     *
     * @return string 
     */
    public function getOrganizationName()
    {
        return $this->organization_name;
    }

    /**
     * Set organization_edrpou
     *
     * @param string $organizationEdrpou
     * @return Invoice
     */
    public function setOrganizationEdrpou($organizationEdrpou)
    {
        $this->organization_edrpou = $organizationEdrpou;
    
        return $this;
    }

    /**
     * Get organization_edrpou
     *
     * @return string 
     */
    public function getOrganizationEdrpou()
    {
        return $this->organization_edrpou;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Invoice
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set organization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organization
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
     * @var boolean
     */
    private $court;


    /**
     * Set court
     *
     * @param boolean $court
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
}