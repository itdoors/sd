<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClaimTarget
 *
 * @ORM\Table(name="sd_claim_target")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *  "movable" = "MovableClaimTarget",
 *  "immovable" = "ImmovableClaimTarget"})
 * @ORM\Entity()
 */
class ClaimTarget
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="SD\BusinessRoleBundle\Entity\Client")
     * @ORM\JoinTable(name="sd_claim_targets_clients",
     *   joinColumns={@ORM\JoinColumn(name="target_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="client_id", referencedColumnName="id")}
     *   )
     */
    protected $linkedClients;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->linkedClients = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add linkedClients
     *
     * @param \SD\BusinessRoleBundle\Entity\Client $linkedClients
     *
     * @return ClaimTarget
     */
    public function addLinkedClient(\SD\BusinessRoleBundle\Entity\Client $linkedClients)
    {
        $this->linkedClients[] = $linkedClients;
    
        return $this;
    }

    /**
     * Remove linkedClients
     *
     * @param \SD\BusinessRoleBundle\Entity\Client $linkedClients
     */
    public function removeLinkedClient(\SD\BusinessRoleBundle\Entity\Client $linkedClients)
    {
        $this->linkedClients->removeElement($linkedClients);
    }

    /**
     * Get linkedClients
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLinkedClients()
    {
        return $this->linkedClients;
    }
}
