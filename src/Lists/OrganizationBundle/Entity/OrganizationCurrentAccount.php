<?php

namespace Lists\OrganizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrganizationCurrentAccount
 */
class OrganizationCurrentAccount
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
     * @var \Lists\OrganizationBundle\Entity\OrganizationCurrentAccountType
     */
    private $typeAccount;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $organization;

    /**
     * @var \Lists\OrganizationBundle\Entity\Bank
     */
    private $bank;

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
     * @return OrganizationCurrentAccount
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
     * Set typeAccount
     *
     * @param \Lists\OrganizationBundle\Entity\OrganizationCurrentAccountType $typeAccount
     *
     * @return OrganizationCurrentAccount
     */
    public function setTypeAccount (\Lists\OrganizationBundle\Entity\OrganizationCurrentAccountType $typeAccount = null)
    {
        $this->typeAccount = $typeAccount;

        return $this;
    }
    /**
     * Get typeAccount
     *
     * @return \Lists\OrganizationBundle\Entity\OrganizationCurrentAccountType 
     */
    public function getTypeAccount ()
    {
        return $this->typeAccount;
    }
    /**
     * Set organization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organization
     *
     * @return OrganizationCurrentAccount
     */
    public function setOrganization (\Lists\OrganizationBundle\Entity\Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }
    /**
     * Get organization
     *
     * @return \Lists\OrganizationBundle\Entity\Organization 
     */
    public function getOrganization ()
    {
        return $this->organization;
    }
    /**
     * Set bank
     *
     * @param \Lists\OrganizationBundle\Entity\Bank $bank
     *
     * @return OrganizationCurrentAccount
     */
    public function setBank (\Lists\OrganizationBundle\Entity\Bank $bank = null)
    {
        $this->bank = $bank;

        return $this;
    }
    /**
     * Get bank
     *
     * @return \Lists\OrganizationBundle\Entity\Bank 
     */
    public function getBank ()
    {
        return $this->bank;
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
     * @var \Lists\OrganizationBundle\Entity\Currency
     */
    private $currency;

    /**
     * Set currency
     *
     * @param \Lists\OrganizationBundle\Entity\Currency $currency
     *
     * @return OrganizationCurrentAccount
     */
    public function setCurrency (\Lists\OrganizationBundle\Entity\Currency $currency = null)
    {
        $this->currency = $currency;

        return $this;
    }
    /**
     * Get currency
     *
     * @return \Lists\OrganizationBundle\Entity\Currency 
     */
    public function getCurrency ()
    {
        return $this->currency;
    }
}
