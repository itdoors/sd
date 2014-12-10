<?php

namespace ITDoors\ControllingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * InvoiceMessage
 */
class InvoiceMessage
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $invoiceId;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
    private $contactId;

    /**
     * @var string
     */
    private $note;

    /**
     * @var \DateTime
     */
    private $createdate;

    /**
     * @var \ITDoors\ControllingBundle\Entity\Invoice
     */
    private $invoice;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \Lists\ContactBundle\Entity\ModelContact
     */
    private $contact;

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
     * Set invoiceId
     *
     * @param integer $invoiceId
     * 
     * @return InvoiceMessage
     */
    public function setInvoiceId ($invoiceId)
    {
        $this->invoiceId = $invoiceId;

        return $this;
    }
    /**
     * Get invoiceId
     *
     * @return integer 
     */
    public function getInvoiceId ()
    {
        return $this->invoiceId;
    }
    /**
     * Set userId
     *
     * @param integer $userId
     * 
     * @return InvoiceMessage
     */
    public function setUserId ($userId)
    {
        $this->userId = $userId;

        return $this;
    }
    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId ()
    {
        return $this->userId;
    }
    /**
     * Set contactId
     *
     * @param integer $contactId
     * 
     * @return InvoiceMessage
     */
    public function setContactId ($contactId)
    {
        $this->contactId = $contactId;

        return $this;
    }
    /**
     * Get contactId
     *
     * @return integer 
     */
    public function getContactId ()
    {
        return $this->contactId;
    }
    /**
     * Set note
     *
     * @param string $note
     * 
     * @return InvoiceMessage
     */
    public function setNote ($note)
    {
        $this->note = $note;

        return $this;
    }
    /**
     * Get note
     *
     * @return string 
     */
    public function getNote ()
    {
        return $this->note;
    }
    /**
     * Set createdate
     *
     * @param \DateTime $createdate
     * 
     * @return InvoiceMessage
     */
    public function setCreatedate ($createdate)
    {
        $this->createdate = $createdate;

        return $this;
    }
    /**
     * Get createdate
     *
     * @return \DateTime 
     */
    public function getCreatedate ()
    {
        return $this->createdate;
    }
    /**
     * Set invoice
     *
     * @param \ITDoors\ControllingBundle\Entity\Invoice $invoice
     * 
     * @return InvoiceMessage
     */
    public function setInvoice (\ITDoors\ControllingBundle\Entity\Invoice $invoice = null)
    {
        $this->invoice = $invoice;

        return $this;
    }
    /**
     * Get invoice
     *
     * @return \ITDoors\ControllingBundle\Entity\Invoice 
     */
    public function getInvoice ()
    {
        return $this->invoice;
    }
    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * 
     * @return InvoiceMessage
     */
    public function setUser (\SD\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }
    /**
     * Get user
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getUser ()
    {
        return $this->user;
    }
    /**
     * Set contact
     *
     * @param \Lists\ContactBundle\Entity\ModelContact $contact
     * 
     * @return InvoiceMessage
     */
    public function setContact (\Lists\ContactBundle\Entity\ModelContact $contact = null)
    {
        $this->contact = $contact;

        return $this;
    }
    /**
     * Get contact
     *
     * @return \Lists\ContactBundle\Entity\ModelContact 
     */
    public function getContact ()
    {
        return $this->contact;
    }
    /**
     * @var string
     */
    private $file;


    /**
     * Set file
     *
     * @param string $file
     * 
     * @return InvoiceMessage
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }
    /**
     * getAbsolutePath
     *
     * @return null|string
     */
    public function getAbsolutePath ()
    {
        return null === $this->getFile() ? null : $this->getUploadRootDir() . '/' . $this->getFile();
    }
    /**
     * getWebPath
     *
     * @return null|string
     */
    public function getWebPath ()
    {
        return null === $this->getFile() ? null : $this->getUploadDir() . '/' . $this->getFile();
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
        $uploadDir = __DIR__ . '/../../../../web/uploads';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755);
        }
        if (!is_dir($uploadDir.'/invoice')) {
            mkdir($uploadDir.'/invoice', 0755);
        }
        if (!is_dir($uploadDir.'/invoice/scan')) {
            mkdir($uploadDir.'/invoice/scan', 0755);
        }
        if (!is_dir($uploadDir.'/invoice/scan/'.$this->getInvoiceId())) {
            mkdir($uploadDir.'/invoice/scan/'.$this->getInvoiceId(), 0755);
        }

        return '/uploads/invoice/scan/'.$this->getInvoiceId();
    }

    private $fileTepm;

    /**
     * Sets fileTepm.
     *
     * @param UploadedFile $fileTepm
     */
    public function setFileTepm (UploadedFile $fileTepm = null)
    {
        $this->fileTepm = $fileTepm;
    }
    /**
     * Get fileTepm.
     *
     * @return UploadedFile
     */
    public function getFileTepm ()
    {
        return $this->fileTepm;
    }
    /**
     * upload
     */
    public function upload ()
    {
        if (null === $this->getFileTepm()) {
            return;
        }
        $fileExtension = $this->getFileTepm()->getClientOriginalExtension();
        $filename = uniqid() . '.' . $fileExtension;
        $uploadDir = $this->getUploadRootDir();
        $this->getFileTepm()->move($uploadDir, $filename);
        $this->setFile($filename);
        $this->fileTepm = null;
    }
}
