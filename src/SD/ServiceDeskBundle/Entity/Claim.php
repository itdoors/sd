<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Claim
 *
 * @ORM\Table(name="sd_claim", options={
 *  "comment" = "ServiceDeskBundle:Claim"
 *  })
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *  "claim" = "Claim",
 *  "claimOnce" = "ClaimOnce",
 *  "claimDep" = "ClaimDepartment"})
 * @ORM\Entity(repositoryClass="ClaimRepository")
 */
class Claim
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
     * @var ClaimType
     *
     * @ORM\Column(name="type", type="claimType")
     */
    protected $type;

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
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="closedAt", type="datetime", nullable=true)
     */
    protected $closedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="akt_date", type="datetime", nullable=true)
     */
    protected $aktDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="bill_date", type="datetime", nullable=true)
     */
    protected $billDate;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    protected $text;

    /**
     * @var string
     *
     * @ORM\Column(name="akt", type="text", nullable=true)
     */
    protected $akt;

    /**
     * @var string
     *
     * @ORM\Column(name="smeta", type="text", nullable=true)
     */
    protected $smeta;

    /**
     * @var string
     *
     * @ORM\Column(name="smeta_cost", type="text", nullable=true)
     */
    protected $smetaCost;

    /**
     * @var FinStatusType
     *
     * @ORM\Column(name="fin_status", type="finStatusType", nullable=true)
     */
    protected $finStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="mpk", type="string", length=255, nullable=true)
     */
    protected $mpk;

    /**
     * @var string
     *
     * @ORM\Column(name="scale", type="string", length=255, nullable=true)
     */
    protected $scale;

    /**
     * @var boolean
     *
     * @ORM\Column(name="disabled", type="boolean")
     */
    protected $disabled = false;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="SD\ServiceDeskBundle\Entity\ClaimMessage", mappedBy="claim")
     */
    protected $messages;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="SD\ServiceDeskBundle\Entity\ClaimFinanceRecord", mappedBy="claim")
     */
    protected $financeRecords;

    /**
     * @var SD\ServiceDeskBundle\Entity\ClaimImportance
     *
     * @ORM\ManyToOne(targetEntity="SD\ServiceDeskBundle\Entity\ClaimImportance")
     * @ORM\JoinColumn(name="importance_id", referencedColumnName="id")
     */
    protected $importance;

    /**
     * @var Lists\OrganizationBundle\Entity\Organization
     *
     * @ORM\ManyToOne(targetEntity="Lists\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="self_org_id", referencedColumnName="id")
     */
    protected $selfOrganization;

    /**
     * @var \SD\BusinessRoleBundle\Entity\Client
     *
     * @ORM\ManyToOne(targetEntity="SD\BusinessRoleBundle\Entity\Client", inversedBy="claims", fetch="EAGER")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="SD\ServiceDeskBundle\Entity\ClaimPerformerRule", mappedBy="claim")
     */
    protected $claimPerformerRules;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ITDoors\FileAccessBundle\Entity\ClaimFile", mappedBy="claim", cascade={"persist"})
     */
    protected $files;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->claimPerformerRules = new \Doctrine\Common\Collections\ArrayCollection();
        $this->financeRecords = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set type
     *
     * @param string $type
     * 
     * @return Claim
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set status
     *
     * @param string $status
     * 
     * @return Claim
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
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * 
     * @return Claim
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set closedAt
     *
     * @param \DateTime $closedAt
     * 
     * @return Claim
     */
    public function setClosedAt($closedAt)
    {
        $this->closedAt = $closedAt;

        return $this;
    }

    /**
     * Get closedAt
     *
     * @return \DateTime 
     */
    public function getClosedAt()
    {
        return $this->closedAt;
    }

    /**
     * Set disabled
     *
     * @param boolean $disabled
     * 
     * @return Claim
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * Get disabled
     *
     * @return boolean 
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

    /**
     * Add messages
     *
     * @param \SD\ServiceDeskBundle\Entity\ClaimMessage $messages
     * 
     * @return Claim
     */
    public function addMessage(\SD\ServiceDeskBundle\Entity\ClaimMessage $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \SD\ServiceDeskBundle\Entity\ClaimMessage $messages
     */
    public function removeMessage(\SD\ServiceDeskBundle\Entity\ClaimMessage $messages)
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
     * Set customer
     *
     * @param \SD\BusinessRoleBundle\Entity\Client $customer
     * 
     * @return Claim
     */
    public function setCustomer(\SD\BusinessRoleBundle\Entity\Client $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \SD\BusinessRoleBundle\Entity\Client 
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Claim
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Add claimPerformerRule
     *
     * @param \SD\ServiceDeskBundle\Entity\ClaimPerformerRule $claimPerformerRule
     * 
     * @return Claim
     */
    public function addClaimPerformerRule(\SD\ServiceDeskBundle\Entity\ClaimPerformerRule $claimPerformerRule)
    {
        $this->claimPerformerRules[] = $claimPerformerRule;

        return $this;
    }

    /**
     * Remove claimPerformerRule
     *
     * @param \SD\ServiceDeskBundle\Entity\ClaimPerformerRule $claimPerformerRule
     */
    public function removeClaimPerformerRule(\SD\ServiceDeskBundle\Entity\ClaimPerformerRule $claimPerformerRule)
    {
        $this->claimPerformerRules->removeElement($claimPerformerRule);
    }

    /**
     * Get claimPerformerRules
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClaimPerformerRules()
    {
        return $this->claimPerformerRules;
    }

    /**
     * Add file
     *
     * @param \ITDoors\FileAccessBundle\Entity\ClaimFile $file
     * 
     * @return Claim
     */
    public function addFile(\ITDoors\FileAccessBundle\Entity\ClaimFile $file)
    {
        $file->setClaim($this);
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file
     *
     * @param \ITDoors\FileAccessBundle\Entity\ClaimFile $file
     */
    public function removeFile(\ITDoors\FileAccessBundle\Entity\ClaimFile $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set importance
     *
     * @param \SD\ServiceDeskBundle\Entity\ClaimImportance $importance
     * 
     * @return Claim
     */
    public function setImportance(\SD\ServiceDeskBundle\Entity\ClaimImportance $importance = null)
    {
        $this->importance = $importance;

        return $this;
    }

    /**
     * Get importance
     *
     * @return \SD\ServiceDeskBundle\Entity\ClaimImportance
     */
    public function getImportance()
    {
        return $this->importance;
    }

    /**
     * Set mpk
     *
     * @param string $mpk
     *
     * @return Claim
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
     * Set selfOrganization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $selfOrganization
     *
     * @return Claim
     */
    public function setSelfOrganization(\Lists\OrganizationBundle\Entity\Organization $selfOrganization = null)
    {
        $this->selfOrganization = $selfOrganization;

        return $this;
    }

    /**
     * Get selfOrganization
     *
     * @return \Lists\OrganizationBundle\Entity\Organization
     */
    public function getSelfOrganization()
    {
        return $this->selfOrganization;
    }

    /**
     * Set scale
     *
     * @param string $scale
     *
     * @return Claim
     */
    public function setScale($scale)
    {
        $this->scale = $scale;

        return $this;
    }

    /**
     * Get scale
     *
     * @return string
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * Set statusLastModified
     *
     * @param \DateTime $statusLastModified
     *
     * @return Claim
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

    /**
     * Add financeRecords
     *
     * @param \SD\ServiceDeskBundle\Entity\ClaimFinanceRecord $financeRecords
     *
     * @return Claim
     */
    public function addFinanceRecord(\SD\ServiceDeskBundle\Entity\ClaimFinanceRecord $financeRecords)
    {
        $this->financeRecords[] = $financeRecords;

        return $this;
    }

    /**
     * Remove financeRecords
     *
     * @param \SD\ServiceDeskBundle\Entity\ClaimFinanceRecord $financeRecords
     */
    public function removeFinanceRecord(\SD\ServiceDeskBundle\Entity\ClaimFinanceRecord $financeRecords)
    {
        $this->financeRecords->removeElement($financeRecords);
    }

    /**
     * Get financeRecords
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFinanceRecords()
    {
        return $this->financeRecords;
    }


    /**
     * Set aktDate
     *
     * @param \DateTime $aktDate
     *
     * @return Claim
     */
    public function setAktDate($aktDate)
    {
        $this->aktDate = $aktDate;

        return $this;
    }

    /**
     * Get aktDate
     *
     * @return \DateTime
     */
    public function getAktDate()
    {
        return $this->aktDate;
    }

    /**
     * Set billDate
     *
     * @param \DateTime $billDate
     *
     * @return Claim
     */
    public function setBillDate($billDate)
    {
        $this->billDate = $billDate;

        return $this;
    }

    /**
     * Get billDate
     *
     * @return \DateTime
     */
    public function getBillDate()
    {
        return $this->billDate;
    }

    /**
     * Set akt
     *
     * @param string $akt
     *
     * @return Claim
     */
    public function setAkt($akt)
    {
        $this->akt = $akt;

        return $this;
    }

    /**
     * Get akt
     *
     * @return string
     */
    public function getAkt()
    {
        return $this->akt;
    }

    /**
     * Set smeta
     *
     * @param string $smeta
     *
     * @return Claim
     */
    public function setSmeta($smeta)
    {
        $this->smeta = $smeta;

        return $this;
    }

    /**
     * Get smeta
     *
     * @return string
     */
    public function getSmeta()
    {
        return $this->smeta;
    }

    /**
     * Set smetaCost
     *
     * @param string $smetaCost
     *
     * @return Claim
     */
    public function setSmetaCost($smetaCost)
    {
        $this->smetaCost = $smetaCost;

        return $this;
    }

    /**
     * Get smetaCost
     *
     * @return string
     */
    public function getSmetaCost()
    {
        return $this->smetaCost;
    }

    /**
     * Set finStatus
     *
     * @param \finStatusType $finStatus
     *
     * @return Claim
     */
    public function setFinStatus(\finStatusType $finStatus)
    {
        $this->finStatus = $finStatus;

        return $this;
    }

    /**
     * Get finStatus
     *
     * @return \finStatusType
     */
    public function getFinStatus()
    {
        return $this->finStatus;
    }

    protected $incomeNDS = 0;

    protected $costsAllNDS = 0;

    protected $profitability = 0;

    public function getIncomeNDS()
    {
        $incomeNDS = 0;
        foreach ($this->financeRecords as $record) {
            $incomeNDS += $record->getIncomeNDS();
        }

        return $incomeNDS;
    }

    public function getCostsAllNDS()
    {
        $costsAllNDS = 0;
        foreach ($this->financeRecords as $record) {
            $costsAllNDS += $record->getCostsNSum() *
                            (1 + $record->getObnal()) *
                            (1 + $record->getNds());

            $costsAllNDS += $record->getCostsNDS();
            $costsAllNDS += $record->getCostsNonNDS();
        }

        return $costsAllNDS;
    }

    public function getProfitability()
    {
        $profitability = 0;
        foreach ($this->financeRecords as $record) {
            $profitability += $record->getProfitability();
        }

        return $profitability;
    }

    public function getProfitabilityProc()
    {
        if ($this->getIncomeNDS() != 0)
            return round($this->getProfitability() / ($this->getIncomeNDS() / 1.2), 2) * 100;
        else
            return 0;
    }
}

// @codingStandardsIgnoreStart
final class ClaimType extends \ITDoors\DBAL\EnumType
{
    const CLEANING = 'cleaning';
    const TECH = 'tech';
    const GREEN_COMFORT_IT = 'green_comfort_it';
    const CATERING = 'catering';
    const COMPLAINT = 'complaint';
    const OPINION = 'opinion';
    const OTHER = 'other';
    const TRANSPORTATION = 'transportation';
    const PROPERTY = 'property';
    const PEST = 'pest';
    const ACT_COLLECTING = 'act_collection';

    protected static $name = 'claimType';

    protected static $values = array(
        self::CLEANING,
        self::TECH,
        self::GREEN_COMFORT_IT,
        self::CATERING,
        self::COMPLAINT,
        self::OPINION,
        self::OTHER,
        self::TRANSPORTATION,
        self::PROPERTY,
        self::PEST,
        self::ACT_COLLECTING
    );
}

final class FinStatusType extends \ITDoors\DBAL\EnumType
{
    const OPENED = 'opened';
    const CLOSED = 'closed';

    protected static $name = 'finStatusType';

    protected static $values = array(
        self::OPENED,
        self::CLOSED
    );
}