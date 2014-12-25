<?php

namespace Lists\OrganizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BankCron
 */
class BankCron
{
    /**
     * __construct
     */
    public function __construct ()
    {
        $this->setDate(new \DateTime());
    }
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $reason;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return BankCron
     */
    public function setDate ($date)
    {
        $this->date = $date;

        return $this;
    }
    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate ()
    {
        return $this->date;
    }
    /**
     * Set status
     *
     * @param string $status
     *
     * @return BankCron
     */
    public function setStatus ($status)
    {
        $this->status = $status;

        return $this;
    }
    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus ()
    {
        return $this->status;
    }
    /**
     * Set description
     *
     * @param string $description
     *
     * @return BankCron
     */
    public function setDescription ($description)
    {
        $this->description = $description;

        return $this;
    }
    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription ()
    {
        return $this->description;
    }
    /**
     * Set reason
     *
     * @param string $reason
     *
     * @return BankCron
     */
    public function setReason ($reason)
    {
        $this->reason = $reason;

        return $this;
    }
    /**
     * Get reason
     *
     * @return string 
     */
    public function getReason ()
    {
        return $this->reason;
    }
    /**
     * Set bank
     *
     * @param \Lists\OrganizationBundle\Entity\Bank $bank
     *
     * @return BankCron
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
}
