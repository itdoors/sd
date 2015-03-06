<?php

namespace SD\BusinessRoleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Entity(repositoryClass="ClientRepository")
 */
class Client extends BusinessRole
{
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="SD\ServiceDeskBundle\Entity\Claim", mappedBy="customer")
     */
    protected $claims;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->claims = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add claims
     *
     * @param \SD\ServiceDeskBundle\Entity\Claim $claims
     * 
     * @return Client
     */
    public function addClaim(\SD\ServiceDeskBundle\Entity\Claim $claims)
    {
        $this->claims[] = $claims;

        return $this;
    }

    /**
     * Remove claims
     *
     * @param \SD\ServiceDeskBundle\Entity\Claim $claims
     */
    public function removeClaim(\SD\ServiceDeskBundle\Entity\Claim $claims)
    {
        $this->claims->removeElement($claims);
    }

    /**
     * Get claims
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClaims()
    {
        return $this->claims;
    }
}
