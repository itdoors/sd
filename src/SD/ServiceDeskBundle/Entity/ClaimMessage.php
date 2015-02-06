<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClaimMessage
 */
class ClaimMessage
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    protected $user;

    /**
     * @var \SD\ServiceDeskBundle\Entity\Claim
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
}
