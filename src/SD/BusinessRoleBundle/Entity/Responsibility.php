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
     * @var \SD\BusinessRoleBundle\Entity\Staff
     *
     * @ORM\ManyToOne(targetEntity="SD\BusinessRoleBundle\Entity\Staff", inversedBy="responsibilities")
     * @ORM\JoinColumn(name="stuff_id", referencedColumnName="id")
     */
    protected $staff;

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
     * Set staff
     *
     * @param \SD\BusinessRoleBundle\Entity\Staff $staff
     * 
     * @return Responsibility
     */
    public function setStaff(\SD\BusinessRoleBundle\Entity\Staff $staff = null)
    {
        $this->staff = $staff;

        return $this;
    }

    /**
     * Get staff
     *
     * @return \SD\BusinessRoleBundle\Entity\Staff 
     */
    public function getStaff()
    {
        return $this->staff;
    }
}
