<?php

namespace Lists\OrganizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bank
 */
class Bank
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $mfo;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId ()
    {
        return $this->id;
    }
    /**
     * Set name
     *
     * @param string $name
     *
     * @return Bank
     */
    public function setName ($name)
    {
        $this->name = $name;

        return $this;
    }
    /**
     * Get name
     *
     * @return string 
     */
    public function getName ()
    {
        return $this->name;
    }
    /**
     * Set mfo
     *
     * @param string $mfo
     *
     * @return Bank
     */
    public function setMfo ($mfo)
    {
        $this->mfo = $mfo;

        return $this;
    }
    /**
     * Get mfo
     *
     * @return string 
     */
    public function getMfo ()
    {
        return $this->mfo;
    }
    /**
     * __toString
     * 
     * @return string
     */
    public function __toString ()
    {
        return $this->getName();
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $currentAccounts;

    /**
     * Constructor
     */
    public function __construct ()
    {
        $this->currentAccounts = new \Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * Add currentAccounts
     *
     * @param \Lists\OrganizationBundle\Entity\OrganizationCurrentAccount $currentAccounts
     * 
     * @return Bank
     */
    public function addCurrentAccount (\Lists\OrganizationBundle\Entity\OrganizationCurrentAccount $currentAccounts)
    {
        $this->currentAccounts[] = $currentAccounts;

        return $this;
    }
    /**
     * Remove currentAccounts
     *
     * @param \Lists\OrganizationBundle\Entity\OrganizationCurrentAccount $currentAccounts
     */
    public function removeCurrentAccount (\Lists\OrganizationBundle\Entity\OrganizationCurrentAccount $currentAccounts)
    {
        $this->currentAccounts->removeElement($currentAccounts);
    }
    /**
     * Get currentAccounts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentAccounts ()
    {
        return $this->currentAccounts;
    }

    /**
     * @var string
     */
    private $guid;

    /**
     * Set guid
     *
     * @param string $guid
     *
     * @return Bank
     */
    public function setGuid ($guid)
    {
        $this->guid = $guid;

        return $this;
    }
    /**
     * Get guid
     *
     * @return string 
     */
    public function getGuid ()
    {
        return $this->guid;
    }
}
