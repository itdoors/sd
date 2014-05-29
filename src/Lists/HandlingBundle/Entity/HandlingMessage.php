<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * HandlingMessage
 */
class HandlingMessage
{
    const ADDITIONAL_TYPE_FUTURE_MESSAGE = 'fm';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdatetime;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $createdate;

    /**
     * @var string
     */
    private $filepath;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var integer
     */
    private $handling_id;

    /**
     * @var \Lists\HandlingBundle\Entity\Handling
     */
    private $handling;

    /**
     * @var \Lists\HandlingBundle\Entity\HandlingMessageType
     */
    private $type;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $user;

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
     * Set createdatetime
     *
     * @param \DateTime $createdatetime
     *
     * @return HandlingMessage
     */
    public function setCreatedatetime($createdatetime)
    {
        $this->createdatetime = $createdatetime;

        return $this;
    }

    /**
     * Get createdatetime
     *
     * @return \DateTime
     */
    public function getCreatedatetime()
    {
        return $this->createdatetime;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return HandlingMessage
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdate
     *
     * @param \DateTime $createdate
     *
     * @return HandlingMessage
     */
    public function setCreatedate($createdate)
    {
        $this->createdate = $createdate;

        return $this;
    }

    /**
     * Get createdate
     *
     * @return \DateTime
     */
    public function getCreatedate()
    {
        return $this->createdate;
    }

    /**
     * Set filepath
     *
     * @param string $filepath
     *
     * @return HandlingMessage
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;

        return $this;
    }

    /**
     * Get filepath
     *
     * @return string
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return HandlingMessage
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set handling_id
     *
     * @param integer $handlingId
     *
     * @return HandlingMessage
     */
    public function setHandlingId($handlingId)
    {
        // @codingStandardsIgnoreStart
        $this->handling_id = $handlingId;
        // @codingStandardsIgnoreEnd
        return $this;
    }

    /**
     * Get handling_id
     *
     * @return integer
     */
    public function getHandlingId()
    {
        // @codingStandardsIgnoreStart
        return $this->handling_id;
        // @codingStandardsIgnoreEnd
    }

    /**
     * Set handling
     *
     * @param \Lists\HandlingBundle\Entity\Handling $handling
     *
     * @return HandlingMessage
     */
    public function setHandling(\Lists\HandlingBundle\Entity\Handling $handling = null)
    {
        $this->handling = $handling;

        if ($handling) {
            $this->setHandlingId($handling->getId());
        }

        return $this;
    }

    /**
     * Get handling
     *
     * @return \Lists\HandlingBundle\Entity\Handling
     */
    public function getHandling()
    {
        return $this->handling;
    }

    /**
     * Set type
     *
     * @param \Lists\HandlingBundle\Entity\HandlingMessageType $type
     *
     * @return HandlingMessage
     */
    public function setType(\Lists\HandlingBundle\Entity\HandlingMessageType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Lists\HandlingBundle\Entity\HandlingMessageType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return HandlingMessage
     */
    public function setUser(\SD\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \SD\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @ORM\PrePersist
     */
    public function doOnPrePersist()
    {
        // Add your code here
    }

    /**
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->filepath
            ? null
            : $this->getUploadRootDir() . '/' . $this->filepath;
    }

    /**
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->filepath
            ? null
            : $this->getUploadDir() . '/' . $this->filepath;
    }

    /**
     * @return null|string
     */
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/handling_message';
    }

    private $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * upload
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to

        $fileExtension = $this->getFile()->getClientOriginalExtension();

        $newFileName = md5(microtime());

        $filepath = $newFileName . '.' . $fileExtension;

        $uploadDir = $this->getUploadRootDir() . DIRECTORY_SEPARATOR . $this->getHandlingId();

        $this->getFile()->move(
            $uploadDir,
            $filepath
        );

        // set the path property to the filename where you've saved the file
        $this->filepath = $filepath;

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * @var string
     */
    private $additionalType;

    /**
     * Set additionalType
     *
     * @param string $additionalType
     *
     * @return HandlingMessage
     */
    public function setAdditionalType($additionalType)
    {
        $this->additionalType = $additionalType;

        return $this;
    }

    /**
     * Get additionalType
     *
     * @return string
     */
    public function getAdditionalType()
    {
        return $this->additionalType;
    }

    /**
     * @var boolean
     */
    private $isBusinessTrip;

    /**
     * Set isBusinessTrip
     *
     * @param boolean $isBusinessTrip
     *
     * @return HandlingMessage
     */
    public function setIsBusinessTrip($isBusinessTrip)
    {
        $this->isBusinessTrip = $isBusinessTrip;

        return $this;
    }

    /**
     * Get isBusinessTrip
     *
     * @return boolean
     */
    public function getIsBusinessTrip()
    {
        return $this->isBusinessTrip;
    }

    /**
     * Is future message
     *
     * @return bool
     */
    public function isFutureMessage()
    {
        return $this->getAdditionalType() == self::ADDITIONAL_TYPE_FUTURE_MESSAGE;
    }

    /**
     * @var \Lists\ContactBundle\Entity\ModelContact
     */
    private $contact;

    /**
     * Set contact
     *
     * @param \Lists\ContactBundle\Entity\ModelContact $contact
     *
     * @return HandlingMessage
     */
    public function setContact(\Lists\ContactBundle\Entity\ModelContact $contact = null)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return \Lists\ContactBundle\Entity\ModelContact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @var integer
     */
    private $contact_id;

    /**
     * Set contact_id
     *
     * @param integer $contactId
     *
     * @return HandlingMessage
     */
    public function setContactId($contactId)
    {
        // @codingStandardsIgnoreStart
        $this->contact_id = $contactId;
        // @codingStandardsIgnoreEnd
        return $this;
    }

    /**
     * Get contact_id
     *
     * @return integer
     */
    public function getContactId()
    {
        // @codingStandardsIgnoreStart
        return $this->contact_id;
        // @codingStandardsIgnoreEnd
    }

    /**
     * @var integer
     */
    private $user_id;

    /**
     * Set user_id
     *
     * @param integer $userId
     *
     * @return HandlingMessage
     */
    public function setUserId($userId)
    {
        // @codingStandardsIgnoreStart
        $this->user_id = $userId;
        // @codingStandardsIgnoreEnd
        return $this;
    }

    /**
     * Get user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        // @codingStandardsIgnoreStart
        return $this->user_id;
        // @codingStandardsIgnoreEnd
    }

    /**
     * @var integer
     */
    private $type_id;

    /**
     * Set type_id
     *
     * @param integer $typeId
     *
     * @return HandlingMessage
     */
    public function setTypeId($typeId)
    {
        // @codingStandardsIgnoreStart
        $this->type_id = $typeId;
        // @codingStandardsIgnoreEnd
        return $this;
    }

    /**
     * Get type_id
     *
     * @return integer
     */
    public function getTypeId()
    {
        // @codingStandardsIgnoreStart
        return $this->type_id;
        // @codingStandardsIgnoreEnd
    }
}
