<?php

namespace SD\BusinessRoleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Responsibility
 *
 * @ORM\Table(name="responsibility")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *  "responsibility" = "Responsibility",
 *  "claimResponsibility" = "ClaimResponsibility"})
 * @ORM\Entity
 */
class Responsibility
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \SD\BusinessRoleBundle\Entity\Stuff
     *
     * @ORM\ManyToOne(targetEntity="SD\BusinessRoleBundle\Entity\Stuff", inversedBy="responsibilities")
     * @ORM\JoinColumn(name="stuff_id", referencedColumnName="id")
     */
    protected $stuff;

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
     * Set stuff
     *
     * @param \SD\BusinessRoleBundle\Entity\Stuff $stuff
     * 
     * @return Responsibility
     */
    public function setStuff(\SD\BusinessRoleBundle\Entity\Stuff $stuff = null)
    {
        $this->stuff = $stuff;

        return $this;
    }

    /**
     * Get stuff
     *
     * @return \SD\BusinessRoleBundle\Entity\Stuff 
     */
    public function getStuff()
    {
        return $this->stuff;
    }
}
