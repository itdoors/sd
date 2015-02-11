<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Claim
 *
 * @ORM\Table(name="sd_claim")
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
     * @ORM\ManyToMany(targetEntity="SD\BusinessRoleBundle\Entity\ClaimCurator")
     * @ORM\JoinTable(name="claims_curators",
     *   joinColumns={
     *     @ORM\JoinColumn(name="claim_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="curator_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $curators;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="SD\BusinessRoleBundle\Entity\ClaimPerformer")
     * @ORM\JoinTable(name="claims_performers",
     *   joinColumns={
     *     @ORM\JoinColumn(name="claim_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="performer_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $performers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->curators = new \Doctrine\Common\Collections\ArrayCollection();
        $this->performers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add curators
     *
     * @param \SD\BusinessRoleBundle\Entity\Stuff $curators
     * 
     * @return Claim
     */
    public function addCurator(\SD\BusinessRoleBundle\Entity\Stuff $curators)
    {
        $this->curators[] = $curators;

        return $this;
    }

    /**
     * Remove curators
     *
     * @param \SD\BusinessRoleBundle\Entity\Stuff $curators
     */
    public function removeCurator(\SD\BusinessRoleBundle\Entity\Stuff $curators)
    {
        $this->curators->removeElement($curators);
    }

    /**
     * Get curators
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurators()
    {
        return $this->curators;
    }

    /**
     * Set curators
     * 
     * @param \SD\BusinessRoleBundle\Entity\Stuff $curators
     *
     * @return Claim
     */
    public function setCurators(\Doctrine\Common\Collections\Collection $curators = null)
    {
        if ($curators) {
            $this->curators = $curators;
        } else {
            $this->curators = new \Doctrine\Common\Collections\ArrayCollection();
        }

        return $this;
    }

    /**
     * Add performers
     *
     * @param \SD\BusinessRoleBundle\Entity\Stuff $performers
     * 
     * @return Claim
     */
    public function addPerformer(\SD\BusinessRoleBundle\Entity\Stuff $performers)
    {
        $this->performers[] = $performers;

        return $this;
    }

    /**
     * Remove performers
     *
     * @param \SD\BusinessRoleBundle\Entity\Stuff $performers
     */
    public function removePerformer(\SD\BusinessRoleBundle\Entity\Stuff $performers)
    {
        $this->performers->removeElement($performers);
    }

    /**
     * Get performers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPerformers()
    {
        return $this->performers;
    }

    /**
     * Set performers
     *
     * @param \SD\BusinessRoleBundle\Entity\Stuff $performers
     *
     * @return Claim
     */
    public function setPerformers(\Doctrine\Common\Collections\Collection $performers = null)
    {
        if ($performers) {
            $this->performers = $performers;
        } else {
            $this->performers = new \Doctrine\Common\Collections\ArrayCollection();
        }

        return $this;
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
