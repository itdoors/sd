<?php

namespace SD\BusinessRoleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stuff
 *
 * @ORM\Entity
 */
class Stuff extends BusinessRole
{
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="SD\BusinessRoleBundle\Entity\Responsibility", mappedBy="stuff")
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
     * @return Stuff
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
