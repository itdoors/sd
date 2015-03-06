<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClaimFinanceRecord
 *
 * @ORM\Table(name="sd_claim_finance")
 * @ORM\Entity()
 */
class ClaimFinanceRecord
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \SD\ServiceDeskBundle\Entity\Claim
     *
     * @ORM\ManyToOne(targetEntity="SD\ServiceDeskBundle\Entity\Claim", inversedBy="financeRecords")
     * @ORM\JoinColumn(name="claim_id", referencedColumnName="id")
     */
    protected $claim;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="SD\ServiceDeskBundle\Entity\CostNal", mappedBy="financeRecord")
     */
    protected $costsN;

    /**
     * @var double
     *
     * @ORM\Column(name="costs_nds", type="float")
     */
    protected $costsNDS = 0;

    /**
     * @var double
     *
     * @ORM\Column(name="costs_nonnds", type="float")
     */
    protected $costsNonNDS = 0;

    /**
     * @var double
     *
     * @ORM\Column(name="income_nonnds", type="float")
     */
    protected $incomeNonNDS = 0;

    /**
     * @var double
     *
     * @ORM\Column(name="income_nds", type="float")
     */
    protected $incomeNDS = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="bill_number", type="string", length=100, nullable=true)
     */
    protected $billNumber;

    /**
     * @var double
     *
     * @ORM\Column(name="nds", type="float")
     */
    protected $nds = 0.2;

    /**
     * @var double
     *
     * @ORM\Column(name="obnal", type="float")
     */
    protected $obnal = 0.12;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_closed", type="boolean")
     */
    protected $isClosed = false;

    /**
     * @var string
     *
     * @ORM\Column(name="work", type="string", length=255, nullable=true)
     */
    protected $work;

    /**
     * @var string
     *
     * @ORM\Column(name="mpk", type="string", length=50, nullable=true)
     */
    protected $mpk;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_type", type="string", length=10, nullable=true)
     */
    protected $paymentType;

    /**
     * @var double
     *
     * @ORM\Column(name="costs_beznalnonnds", type="float")
     */
    protected $costsBeznalNonNDS = 0;

    /**
     * @var StatusType
     *
     * @ORM\Column(name="status", type="statusType")
     */
    protected $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="statusLastModified", type="datetime", nullable=true)
     */
    protected $statusLastModified;

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
     * Set costsNDS
     *
     * @param float $costsNDS
     *
     * @return ClaimFinanceRecord
     */
    public function setCostsNDS($costsNDS)
    {
        $this->costsNDS = $costsNDS;
    
        return $this;
    }

    /**
     * Get costsNDS
     *
     * @return float 
     */
    public function getCostsNDS()
    {
        return $this->costsNDS;
    }

    /**
     * Set costsNonNDS
     *
     * @param float $costsNonNDS
     *
     * @return ClaimFinanceRecord
     */
    public function setCostsNonNDS($costsNonNDS)
    {
        $this->costsNonNDS = $costsNonNDS;
    
        return $this;
    }

    /**
     * Get costsNonNDS
     *
     * @return float 
     */
    public function getCostsNonNDS()
    {
        return $this->costsNonNDS;
    }

    /**
     * Set incomeNDS
     *
     * @param float $incomeNDS
     *
     * @return ClaimFinanceRecord
     */
    public function setIncomeNDS($incomeNDS)
    {
        $this->incomeNDS = $incomeNDS;
    
        return $this;
    }

    /**
     * Get incomeNDS
     *
     * @return float 
     */
    public function getIncomeNDS()
    {
        return $this->incomeNDS;
    }

    /**
     * Set incomeNonNDS
     *
     * @param float $incomeNonNDS
     *
     * @return ClaimFinanceRecord
     */
    public function setIncomeNonNDS($incomeNonNDS)
    {
        $this->incomeNonNDS = $incomeNonNDS;
    
        return $this;
    }

    /**
     * Get incomeNonNDS
     *
     * @return float 
     */
    public function getIncomeNonNDS()
    {
        return $this->incomeNonNDS;
    }

    /**
     * Set billNumber
     *
     * @param string $billNumber
     *
     * @return ClaimFinanceRecord
     */
    public function setBillNumber($billNumber)
    {
        $this->billNumber = $billNumber;
    
        return $this;
    }

    /**
     * Get billNumber
     *
     * @return string 
     */
    public function getBillNumber()
    {
        return $this->billNumber;
    }

    /**
     * Get profitability
     *
     * @return float 
     */
    public function getProfitability()
    {
        return round(
                $this->getIncomeNDS() / 1.2 -
                ($this->getCostsNSum() * ($this->nds + 1)) * ($this->obnal + 1) -
                $this->costsNonNDS -
                $this->costsNDS / 1.2
                , 2);
    }

    /**
     * Get profitability
     *
     * @return float
     */
    public function getProfitabilityProc()
    {
        if ($this->getIncomeNDS() != 0)
            return round($this->getProfitability() / ($this->getIncomeNDS() / 1.2), 2) * 100;
        else
            return 0;
    }

    /**
     * Set nds
     *
     * @param float $nds
     *
     * @return ClaimFinanceRecord
     */
    public function setNds($nds)
    {
        $this->nds = $nds;
    
        return $this;
    }

    /**
     * Get nds
     *
     * @return float 
     */
    public function getNds()
    {
        return $this->nds;
    }

    /**
     * Set obnal
     *
     * @param float $obnal
     *
     * @return ClaimFinanceRecord
     */
    public function setObnal($obnal)
    {
        $this->obnal = $obnal;
    
        return $this;
    }

    /**
     * Get obnal
     *
     * @return float 
     */
    public function getObnal()
    {
        return $this->obnal;
    }

    /**
     * Set isClosed
     *
     * @param boolean $isClosed
     *
     * @return ClaimFinanceRecord
     */
    public function setIsClosed($isClosed)
    {
        $this->isClosed = $isClosed;
    
        return $this;
    }

    /**
     * Get isClosed
     *
     * @return boolean 
     */
    public function getIsClosed()
    {
        return $this->isClosed;
    }

    /**
     * Set work
     *
     * @param string $work
     *
     * @return ClaimFinanceRecord
     */
    public function setWork($work)
    {
        $this->work = $work;
    
        return $this;
    }

    /**
     * Get work
     *
     * @return string 
     */
    public function getWork()
    {
        return $this->work;
    }

    /**
     * Set mpk
     *
     * @param string $mpk
     *
     * @return ClaimFinanceRecord
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
     * Set paymentType
     *
     * @param string $paymentType
     *
     * @return ClaimFinanceRecord
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;
    
        return $this;
    }

    /**
     * Get paymentType
     *
     * @return string 
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Set costsBeznalNonNDS
     *
     * @param float $costsBeznalNonNDS
     *
     * @return ClaimFinanceRecord
     */
    public function setCostsBeznalNonNDS($costsBeznalNonNDS)
    {
        $this->costsBeznalNonNDS = $costsBeznalNonNDS;
    
        return $this;
    }

    /**
     * Get costsBeznalNonNDS
     *
     * @return float 
     */
    public function getCostsBeznalNonNDS()
    {
        return $this->costsBeznalNonNDS;
    }

    /**
     * Set claim
     *
     * @param \SD\ServiceDeskBundle\Entity\Claim $claim
     *
     * @return ClaimFinanceRecord
     */
    public function setClaim(\SD\ServiceDeskBundle\Entity\Claim $claim = null)
    {
        $this->claim = $claim;
    
        return $this;
    }

    /**
     * Get claim
     *
     * @return \SD\ServiceDeskBundle\Entity\Claim 
     */
    public function getClaim()
    {
        return $this->claim;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->costsN = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add costsN
     *
     * @param \SD\ServiceDeskBundle\Entity\CostNal $costsN
     *
     * @return ClaimFinanceRecord
     */
    public function addCostsN(\SD\ServiceDeskBundle\Entity\CostNal $costsN)
    {
        $this->costsN[] = $costsN;
    
        return $this;
    }

    /**
     * Remove costsN
     *
     * @param \SD\ServiceDeskBundle\Entity\CostNal $costsN
     */
    public function removeCostsN(\SD\ServiceDeskBundle\Entity\CostNal $costsN)
    {
        $this->costsN->removeElement($costsN);
    }

    /**
     * Get costsN
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCostsN()
    {
        return $this->costsN;
    }

    public function getCostsNSum()
    {
        $costsNSum = 0;
        foreach ($this->costsN as $cost) {
            $costsNSum += $cost->getValue();
        }

        return $costsNSum;
    }

    /**
     * Set status
     *
     * @param statusType $status
     *
     * @return ClaimFinanceRecord
     */
    public function setStatus($status)
    {
        $this->status = $status;
        $this->setStatusLastModified(new \DateTime());
    
        return $this;
    }

    /**
     * Get status
     *
     * @return statusType 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set statusLastModified
     *
     * @param \DateTime $statusLastModified
     *
     * @return ClaimFinanceRecord
     */
    public function setStatusLastModified($statusLastModified)
    {
        $this->statusLastModified = $statusLastModified;
    
        return $this;
    }

    /**
     * Get statusLastModified
     *
     * @return \DateTime 
     */
    public function getStatusLastModified()
    {
        return $this->statusLastModified;
    }
}