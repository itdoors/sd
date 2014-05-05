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
     * @var boolean
     */
    private $postponement;

    /**
     * @var \DateTime
     */
    private $dateEnd;

    /**
     * @var \DateTime
     */
    private $dateFact;

    /**
     * @var string
     */
    private $dogovorName;

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
    private $cityName;

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
    private $description;

    /**
     * @var boolean
     */
    private $court;

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
     * Set postponement
     *
     * @param boolean $postponement
     * 
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
     * Set cityName
     *
     * @param string $cityName
     * 
     * @return Invoice
     */
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;

        return $this;
    }

    /**
     * Get cityName
     *
     * @return string 
     */
    public function getCityName()
    {
        return $this->cityName;
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
     * Set description
     *
     * @param string $description
     * 
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
    private $regionName;

    /**
     * @var \Lists\DogovorBundle\Entity\Region
     */
    private $region;


    /**
     * Set regionName
     *
     * @param string $regionName
     * 
     * @return Invoice
     */
    public function setRegionName($regionName)
    {
        $this->regionName = $regionName;

        return $this;
    }

    /**
     * Get regionName
     *
     * @return string 
     */
    public function getRegionName()
    {
        return $this->regionName;
    }

    /**
     * Set region
     *
     * @param \Lists\DogovorBundle\Entity\Region $region
     * 
     * @return Invoice
     */
    public function setRegion(\Lists\DogovorBundle\Entity\Region $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \Lists\DogovorBundle\Entity\Region 
     */
    public function getRegion()
    {
        return $this->region;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $histories;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->histories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add histories
     *
     * @param \ITDoors\ControllingBundle\Entity\InvoiceHistory $histories
     * 
     * @return Invoice
     */
    public function addHistorie(\ITDoors\ControllingBundle\Entity\InvoiceHistory $histories)
    {
        $this->histories[] = $histories;

        return $this;
    }

    /**
     * Remove histories
     *
     * @param \ITDoors\ControllingBundle\Entity\InvoiceHistory $histories
     */
    public function removeHistorie(\ITDoors\ControllingBundle\Entity\InvoiceHistory $histories)
    {
        $this->histories->removeElement($histories);
    }

    /**
     * Get histories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHistories()
    {
        return $this->histories;
    }
}