<?php

namespace SD\BusinessRoleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Staff
 *
 * @ORM\Entity
 */
class Staff extends BusinessRole
{
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="SD\BusinessRoleBundle\Entity\Responsibility", mappedBy="staff")
     */
    protected $responsibilities;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->responsibilities = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add responsibility
     *
     * @param \SD\BusinessRoleBundle\Entity\Responsibility $responsibility
     * 
     * @return Staff
     */
    public function addResponsibility(\SD\BusinessRoleBundle\Entity\Responsibility $responsibility)
    {
        $this->responsibilities[] = $responsibility;

        return $this;
    }

    /**
     * Remove responsibility
     *
     * @param \SD\BusinessRoleBundle\Entity\Responsibility $responsibility
     */
    public function removeResponsibility(\SD\BusinessRoleBundle\Entity\Responsibility $responsibility)
    {
        $this->responsibilities->removeElement($responsibility);
    }

    /**
     * Get responsibilities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResponsibilities()
    {
        return $this->responsibilities;
    }
}
