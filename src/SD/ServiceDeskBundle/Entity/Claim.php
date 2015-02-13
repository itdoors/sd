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
     * @ORM\Column(name="types", type="claimType")
     */
    protected $types;

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
     * @ORM\ManyToMany(targetEntity="SD\ServiceDeskBundle\Entity\ClaimPerformerRule")
     * @ORM\JoinTable(name="claim_claim_performer_rule",
     *   joinColumns={
     *     @ORM\JoinColumn(name="claim_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="claim_performer_rule_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $claimPerformerRules;

    /**
     * Constructor
     */
    public function __construct()
    {
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
     * Set types
     *
     * @param string $types
     * 
     * @return Claim
     */
    public function setTypes($types)
    {
        $this->types = $types;

        return $this;
    }

    /**
     * Get types
     *
     * @return string 
     */
    public function getTypes()
    {
        return $this->types;
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
}

// @codingStandardsIgnoreStart
class ClaimType extends \ITDoors\DBAL\EnumType
{
    const VISIBLE = 'visible';
    const INVISIBLE = 'invisible';
    protected static $name = __CLASS__;

//     protected static $name = 'claimType';
    protected static $values = array(
        ClaimType::VISIBLE,
        ClaimType::INVISIBLE
    );
}

class StatusType extends \ITDoors\DBAL\EnumType
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    protected static $name = __CLASS__;

//     protected static $name = 'statusType';
    protected static $values = array(
        StatusType::ACTIVE,
        StatusType::INACTIVE
                    
    );
}

class ImportanceType extends \ITDoors\DBAL\EnumType
{
    const HOT = 'hot';
    const WEAK = 'weak';
    protected static $name = __CLASS__;

//     protected static $name = 'importanceType';
    protected static $values = array(
        self::HOT,
        self::WEAK
    );
}
