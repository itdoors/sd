<?php

namespace SD\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Deputy
 */
class Deputy
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \SD\UserBundle\Entity\Stuff
     */
    private $forStuff;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $deputyStuffs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->deputyStuffs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set forStuff
     *
     * @param \SD\UserBundle\Entity\Stuff $forStuff
     * 
     * @return Deputy
     */
    public function setForStuff(\SD\UserBundle\Entity\Stuff $forStuff = null)
    {
        $this->forStuff = $forStuff;

        return $this;
    }

    /**
     * Get forStuff
     *
     * @return \SD\UserBundle\Entity\Stuff 
     */
    public function getForStuff()
    {
        return $this->forStuff;
    }

    /**
     * Add deputyStuff
     *
     * @param \SD\UserBundle\Entity\Stuff $deputyStuff
     * 
     * @return Deputy
     */
    public function addDeputyStuff(\SD\UserBundle\Entity\Stuff $deputyStuff)
    {
        $this->deputyStuffs[] = $deputyStuff;

        return $this;
    }

    /**
     * Remove deputyStuff
     *
     * @param \SD\UserBundle\Entity\Stuff $deputyStuff
     */
    public function removeDeputyStuff(\SD\UserBundle\Entity\Stuff $deputyStuff)
    {
        $this->deputyStuffs->removeElement($deputyStuff);
    }

    /**
     * Get deputyStuffs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDeputyStuffs()
    {
        return $this->deputyStuffs;
    }

    /**
     * Set deputyStuffs
     *
     * @param \Doctrine\Common\Collections\Collection  $deputyStuffs
     */
    public function setDeputyStuffs(\Doctrine\Common\Collections\Collection  $deputyStuffs)
    {
        $this->deputyStuffs = $deputyStuffs;
    }
}
