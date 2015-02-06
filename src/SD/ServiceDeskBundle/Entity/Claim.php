<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Claim
 */
class Claim
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $types;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $importance;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $closedAt;

    /**
     * @var boolean
     */
    protected $disabled = false;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $messages;

    /**
     * @var \SD\ServiceDeskBundle\Entity\Client
     */
    protected $customer;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $curators;

    /**
     * @var \Doctrine\Common\Collections\Collection
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
     * @param \SD\ServiceDeskBundle\Entity\Client $customer
     * 
     * @return Claim
     */
    public function setCustomer(\SD\ServiceDeskBundle\Entity\Client $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \SD\ServiceDeskBundle\Entity\Client 
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Add curators
     *
     * @param \SD\ServiceDeskBundle\Entity\ClaimCurator $curators
     * 
     * @return Claim
     */
    public function addCurator(\SD\ServiceDeskBundle\Entity\ClaimCurator $curators)
    {
        $this->curators[] = $curators;

        return $this;
    }

    /**
     * Remove curators
     *
     * @param \SD\ServiceDeskBundle\Entity\ClaimCurator $curators
     */
    public function removeCurator(\SD\ServiceDeskBundle\Entity\ClaimCurator $curators)
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
     * @param \SD\ServiceDeskBundle\Entity\ClaimPerformer $performers
     * 
     * @return Claim
     */
    public function addPerformer(\SD\ServiceDeskBundle\Entity\ClaimPerformer $performers)
    {
        $this->performers[] = $performers;

        return $this;
    }

    /**
     * Remove performers
     *
     * @param \SD\ServiceDeskBundle\Entity\ClaimPerformer $performers
     */
    public function removePerformer(\SD\ServiceDeskBundle\Entity\ClaimPerformer $performers)
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
