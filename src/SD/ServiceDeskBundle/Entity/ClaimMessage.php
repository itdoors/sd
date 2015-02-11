<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClaimMessage
 *
 * @ORM\Table(name="sd_claim_message")
 * @ORM\Entity
 */
class ClaimMessage
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
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    protected $text;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_visible", type="boolean")
     */
    protected $visible;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \SD\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="SD\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var \SD\ServiceDeskBundle\Entity\Claim
     *
     * @ORM\ManyToOne(targetEntity="SD\ServiceDeskBundle\Entity\Claim", inversedBy="messages")
     * @ORM\JoinColumn(name="claim_id", referencedColumnName="id")
     */
    protected $claim;

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
     * Set text
     *
     * @param string $text
     * 
     * @return ClaimMessage
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * 
     * @return ClaimMessage
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
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * 
     * @return ClaimMessage
     */
    public function setUser(\SD\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set claim
     *
     * @param \SD\ServiceDeskBundle\Entity\Claim $claim
     * 
     * @return ClaimMessage
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
     * Set visibility
     *
     * @param boolean $visible
     *
     * @return ClaimMessage
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }
    
    /**
     * Get visibility
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }
}
