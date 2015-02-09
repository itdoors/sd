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
 *  "claimDep" = "ClaimDep"})
 * @ORM\Entity
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
     * @ORM\ManyToOne(targetEntity="SD\BusinessRoleBundle\Entity\Client", inversedBy="claims")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="SD\BusinessRoleBundle\Entity\Stuff")
     * @ORM\JoinTable(name="claims_curators",
     *   joinColumns={
     *     @ORM\JoinColumn(name="claim_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="stuff_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $curators;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="SD\BusinessRoleBundle\Entity\Stuff")
     * @ORM\JoinTable(name="claims_performers",
     *   joinColumns={
     *     @ORM\JoinColumn(name="claim_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="stuff_id", referencedColumnName="id")
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
}

// @codingStandardsIgnoreStart
class ClaimType extends \ITDoors\DBAL\EnumType
{
    protected $name = 'claimType';
    protected $values = array('visible', 'invisible');
}

class StatusType extends \ITDoors\DBAL\EnumType
{
    protected $name = 'statusType';
    protected $values = array('active', 'inactive');
}

class ImportanceType extends \ITDoors\DBAL\EnumType
{
    protected $name = 'importanceType';
    protected $values = array('hot', 'weak');
}
