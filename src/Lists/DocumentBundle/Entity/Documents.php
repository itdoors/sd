<?php

namespace Lists\DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Documents
 */
class Documents
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
     * @var \DateTime
     */
    private $datetime;

    /**
     * @var \DateTime
     */
    private $createdatetime;

    /**
     * @var string
     */
    private $filepath;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var \Lists\DocumentBundle\Entity\DocumentsType
     */
    private $documentsType;


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
     * Set name
     *
     * @param string $name
     *
     * @return Documents
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     *
     * @return Documents
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime 
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Set createdatetime
     *
     * @param \DateTime $createdatetime
     *
     * @return Documents
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
     * Set filepath
     *
     * @param string $filepath
     *
     * @return Documents
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
     * Set userId
     *
     * @param integer $userId
     *
     * @return Documents
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set documentsType
     *
     * @param \Lists\DocumentBundle\Entity\DocumentsType $documentsType
     *
     * @return Documents
     */
    public function setDocumentsType(\Lists\DocumentBundle\Entity\DocumentsType $documentsType = null)
    {
        $this->documentsType = $documentsType;

        return $this;
    }

    /**
     * Get documentsType
     *
     * @return \Lists\DocumentBundle\Entity\DocumentsType 
     */
    public function getDocumentsType()
    {
        return $this->documentsType;
    }
    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $user;


    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return Documents
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
     * getAbsolutePath
     *
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->filepath ? null : $this->getUploadRootDir() . '/' . $this->filepath;
    }

    /**
     * getWebPath
     *
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->filepath ? null : $this->getUploadDir() . '/' . $this->filepath;
    }

    /**
     * getUploadRootDir
     *
     * @return string
     */
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    /**
     * getUploadDir
     *
     * @return string
     */
    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/document';
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
        $this->name = htmlentities($this->getFile()->getClientOriginalName());
        $fileExtension = $this->getFile()->getClientOriginalExtension();

        $newFileName = md5(microtime());

        $filepath = $newFileName . '.' . $fileExtension;

        $uploadDir = $this->getUploadRootDir();

        $this->getFile()->move(
            $uploadDir,
            $filepath
        );

        // set the path property to the filename where you've saved the file
        $this->filepath = $filepath;

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }
}