<?php

namespace ITDoors\PayMasterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Lists\OrganizationBundle\Entity\OrganizationCurrentAccount;

/**
 * PayMaster
 */
class PayMaster
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createDatetime;

    /**
     * @var \DateTime
     */
    private $invoiceDate;

    /**
     * @var \DateTime
     */
    private $expectedDate;

    /**
     * @var \DateTime
     */
    private $paymentDate;

    /**
     * @var float
     */
    private $invoiceAmount;

    /**
     * @var boolean
     */
    private $vat;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $scan;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $payer;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $contractor;

    /**
     * @var \Lists\DogovorBundle\Entity\Dogovor
     */
    private $dogovor;

    /**
     * Constructor
     */
    public function __construct ()
    {
        $this->setCreateDatetime(new \DateTime());
    }
    /**
     * __toString
     * 
     * @return string
     */
    public function __toString ()
    {
        return (string) $this->getInvoiceAmount();
    }
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
     * Set createDatetime
     *
     * @param \DateTime $createDatetime
     *
     * @return PayMaster
     */
    public function setCreateDatetime ($createDatetime)
    {
        $this->createDatetime = $createDatetime;

        return $this;
    }
    /**
     * Get createDatetime
     *
     * @return \DateTime 
     */
    public function getCreateDatetime ()
    {
        return $this->createDatetime;
    }
    /**
     * Set invoiceDate
     *
     * @param \DateTime $invoiceDate
     *
     * @return PayMaster
     */
    public function setInvoiceDate ($invoiceDate)
    {
        $this->invoiceDate = $invoiceDate;

        return $this;
    }
    /**
     * Get invoiceDate
     *
     * @return \DateTime 
     */
    public function getInvoiceDate ()
    {
        return $this->invoiceDate;
    }
    /**
     * Set expectedDate
     *
     * @param \DateTime $expectedDate
     *
     * @return PayMaster
     */
    public function setExpectedDate ($expectedDate)
    {
        $this->expectedDate = $expectedDate;

        return $this;
    }
    /**
     * Get expectedDate
     *
     * @return \DateTime 
     */
    public function getExpectedDate ()
    {
        return $this->expectedDate;
    }
    /**
     * Set paymentDate
     *
     * @param \DateTime $paymentDate
     *
     * @return PayMaster
     */
    public function setPaymentDate ($paymentDate)
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }
    /**
     * Get paymentDate
     *
     * @return \DateTime 
     */
    public function getPaymentDate ()
    {
        return $this->paymentDate;
    }
    /**
     * Set invoiceAmount
     *
     * @param float $invoiceAmount
     *
     * @return PayMaster
     */
    public function setInvoiceAmount ($invoiceAmount)
    {
        $this->invoiceAmount = $invoiceAmount;

        return $this;
    }
    /**
     * Get invoiceAmount
     *
     * @return float 
     */
    public function getInvoiceAmount ()
    {
        return $this->invoiceAmount;
    }
    /**
     * Set vat
     *
     * @param boolean $vat
     *
     * @return PayMaster
     */
    public function setVat ($vat)
    {
        $this->vat = $vat;

        return $this;
    }
    /**
     * Get vat
     *
     * @return boolean 
     */
    public function getVat ()
    {
        return $this->vat;
    }
    /**
     * Set description
     *
     * @param string $description
     *
     * @return PayMaster
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
     * Set scan
     *
     * @param string $scan
     *
     * @return PayMaster
     */
    public function setScan ($scan)
    {
        $this->scan = $scan;

        return $this;
    }
    /**
     * Get scan
     *
     * @return string 
     */
    public function getScan ()
    {
        return $this->scan;
    }
    /**
     * Set payer
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $payer
     *
     * @return PayMaster
     */
    public function setPayer (\Lists\OrganizationBundle\Entity\Organization $payer = null)
    {
        $this->payer = $payer;

        return $this;
    }
    /**
     * Get payer
     *
     * @return \Lists\OrganizationBundle\Entity\Organization 
     */
    public function getPayer ()
    {
        return $this->payer;
    }
    /**
     * Set contractor
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $contractor
     *
     * @return PayMaster
     */
    public function setContractor (\Lists\OrganizationBundle\Entity\Organization $contractor = null)
    {
        $this->contractor = $contractor;

        return $this;
    }
    /**
     * Get contractor
     *
     * @return \Lists\OrganizationBundle\Entity\Organization 
     */
    public function getContractor ()
    {
        return $this->contractor;
    }
    /**
     * Set dogovor
     *
     * @param \Lists\DogovorBundle\Entity\Dogovor $dogovor
     *
     * @return PayMaster
     */
    public function setDogovor (\Lists\DogovorBundle\Entity\Dogovor $dogovor = null)
    {
        $this->dogovor = $dogovor;

        return $this;
    }
    /**
     * Get dogovor
     *
     * @return \Lists\DogovorBundle\Entity\Dogovor 
     */
    public function getDogovor ()
    {
        return $this->dogovor;
    }
    /**
     * getAbsolutePath
     *
     * @return null|string
     */
    public function getAbsolutePath ()
    {
        return null === $this->getScan() ? null : $this->getUploadRootDir() . '/' . $this->getScan();
    }
    /**
     * getWebPath
     *
     * @return null|string
     */
    public function getWebPath ()
    {
        return null === $this->getScan() ? null : $this->getUploadDir() . '/' . $this->getScan();
    }
    /**
     * getUploadRootDir
     *
     * @return string
     */
    protected function getUploadRootDir ()
    {
        $dir = __DIR__ . '/../../../../web' . $this->getUploadDir();

        return $dir;
    }
    /**
     * getUploadDir
     *
     * @return string
     */
    protected function getUploadDir ()
    {
        $id = $this->getId();
        if (empty($id)) {
            throw new \Exception('ID empty', 404);
        }
        $uploadDir = __DIR__ . '/../../../../web/uploads';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755);
        }
        if (!is_dir($uploadDir . '/paymaster')) {
            mkdir($uploadDir . '/paymaster', 0755);
        }
        if (!is_dir($uploadDir . '/paymaster/' . $this->getId())) {
            mkdir($uploadDir . '/paymaster/' . $this->getId(), 0755);
        }

        return '/uploads/paymaster/' . $this->getId();
    }

    private $fileTemp;

    /**
     * Sets fileTemp.
     *
     * @param UploadedFile $fileTemp
     */
    public function setFileTemp (UploadedFile $fileTemp = null)
    {
        $this->fileTemp = $fileTemp;
    }
    /**
     * Get fileTemp.
     *
     * @return UploadedFile
     */
    public function getFileTemp ()
    {
        return $this->fileTemp;
    }
    /**
     * upload
     */
    public function upload ()
    {
        if (null === $this->getFileTemp()) {
            return;
        }
        $fileExtension = $this->getFileTemp()->getClientOriginalExtension();
        $filename = uniqid() . '.' . $fileExtension;
        $uploadDir = $this->getUploadRootDir();
        $this->getFileTemp()->move($uploadDir, $filename);
        $this->setScan($filename);
        $this->fileTemp = null;
    }

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $creator;

    /**
     * Set creator
     *
     * @param \SD\UserBundle\Entity\User $creator
     * 
     * @return PayMaster
     */
    public function setCreator (\SD\UserBundle\Entity\User $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }
    /**
     * Get creator
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getCreator ()
    {
        return $this->creator;
    }

    /**
     * @var OrganizationCurrentAccount
     */
    private $currentAccount;

    /**
     * Set currentAccount
     *
     * @param OrganizationCurrentAccount $currentAccount
     *
     * @return PayMaster
     */
    public function setCurrentAccount (OrganizationCurrentAccount $currentAccount = null)
    {
        $this->currentAccount = $currentAccount;

        return $this;
    }
    /**
     * Get currentAccount
     *
     * @return OrganizationCurrentAccount 
     */
    public function getCurrentAccount ()
    {
        return $this->currentAccount;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $mpks;

    /**
     * Add mpks
     *
     * @param \Lists\MpkBundle\Entity\Mpk $mpks
     *
     * @return PayMaster
     */
    public function addMpk (\Lists\MpkBundle\Entity\Mpk $mpks)
    {
        $this->mpks[] = $mpks;

        return $this;
    }
    /**
     * Remove mpks
     *
     * @param \Lists\MpkBundle\Entity\Mpk $mpks
     */
    public function removeMpk (\Lists\MpkBundle\Entity\Mpk $mpks)
    {
        $this->mpks->removeElement($mpks);
    }
    /**
     * Get mpks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMpks ()
    {
        return $this->mpks;
    }

    /**
     * @var string
     */
    private $reason;

    /**
     * Set reason
     *
     * @param string $reason
     *
     * @return PayMaster
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $customers;

    /**
     * Add customers
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $customers
     *
     * @return PayMaster
     */
    public function addCustomer (\Lists\OrganizationBundle\Entity\Organization $customers)
    {
        $this->customers[] = $customers;

        return $this;
    }
    /**
     * Remove customers
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $customers
     */
    public function removeCustomer (\Lists\OrganizationBundle\Entity\Organization $customers)
    {
        $this->customers->removeElement($customers);
    }
    /**
     * Get customers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomers ()
    {
        return $this->customers;
    }

    /**
     * @var \ITDoors\PayMasterBundle\Entity\PayMasterStatus
     */
    private $status;

    /**
     * Set status
     *
     * @param \ITDoors\PayMasterBundle\Entity\PayMasterStatus $status
     *
     * @return PayMaster
     */
    public function setStatus (\ITDoors\PayMasterBundle\Entity\PayMasterStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }
    /**
     * Get status
     *
     * @return \ITDoors\PayMasterBundle\Entity\PayMasterStatus 
     */
    public function getStatus ()
    {
        return $this->status;
    }

    /**
     * @var boolean
     */
    private $isAcceptance;

    /**
     * Set isAcceptance
     *
     * @param boolean $isAcceptance
     *
     * @return PayMaster
     */
    public function setIsAcceptance ($isAcceptance)
    {
        $this->isAcceptance = $isAcceptance;

        return $this;
    }
    /**
     * Get isAcceptance
     *
     * @return boolean 
     */
    public function getIsAcceptance ()
    {
        return $this->isAcceptance;
    }

    /**
     * @var boolean
     */
    private $toPay;

    /**
     * Set toPay
     *
     * @param boolean $toPay
     *
     * @return PayMaster
     */
    public function setToPay ($toPay)
    {
        $this->toPay = $toPay;

        return $this;
    }
    /**
     * Get toPay
     *
     * @return boolean 
     */
    public function getToPay ()
    {
        return $this->toPay;
    }

    /**
     * @var \Lists\OrganizationBundle\Entity\Bank
     */
    private $bank;

    /**
     * Set bank
     *
     * @param \Lists\OrganizationBundle\Entity\Bank $bank
     *
     * @return PayMaster
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
     * @var string
     */
    private $number;


    /**
     * Set number
     *
     * @param string $number
     * @return PayMaster
     */
    public function setNumber($number)
    {
        $this->number = $number;
    
        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }
}