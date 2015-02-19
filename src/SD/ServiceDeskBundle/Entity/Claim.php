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
     * @var ImportanceType
     *
     * @ORM\Column(name="importance", type="importanceType")
     */
    protected $importance;

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
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    protected $text;

    /**
     * @var boolean
     *
     * @ORM\Column(name="disabled", type="boolean")
     */
    protected $disabled;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="SD\ServiceDeskBundle\Entity\ClaimMessage", mappedBy="claim")
     */
    protected $messages;

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
     * @ORM\OneToMany(targetEntity="ITDoors\FileAccessBundle\Entity\ClaimFile", mappedBy="claim")
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
     * Set importance
     *
     * @param string $importance
     * 
     * @return Claim
     */
    public function setImportance($importance)
    {
        $this->importance = $importance;

        return $this;
    }

    /**
     * Get importance
     *
     * @return string 
     */
    public function getImportance()
    {
        return $this->importance;
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

final class StatusType extends \ITDoors\DBAL\EnumType
{
    const DONE = 'sta_sclose_smclose_cclose';
    const OPEN = 'sta_open';
    const SEND = 'sta_sappointed_smwait';
    const IN_PROGRESS = 'sta_sclose_smwait';
    const SUBMITTING = 'sta_sclose_smwait_cwait';
    const CREATING = 'sta_smeta_composу';
    const MATCHED = 'sta_smeta_conform';
    const CANCELED = 'sta_smeta_cancel';
    const ESTIMATING = 'sta_smet';
    const REJECTED = 'sta_sclose_smclose_cwait';
    protected static $name = 'statusType';

    protected static $values = array(
        self::DONE,
        self::OPEN,
        self::SEND,
        self::IN_PROGRESS,
        self::SUBMITTING,
        self::CREATING,
        self::MATCHED,
        self::CANCELED,
        self::ESTIMATING,
        self::REJECTED
    );
}

final class ImportanceType extends \ITDoors\DBAL\EnumType
{
    const PLANNED = 'planned';
    const UNPLANNED = 'unplanned';
    const HOT = 'hot';
    const BROKEN = 'broken';
    const MONTH = 'month';
    protected static $name = 'importanceType';

    protected static $values = array(
        self::PLANNED,
        self::UNPLANNED,
        self::HOT,
        self::BROKEN,
        self::MONTH
    );
}
