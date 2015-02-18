<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * File
 */
class File
{
    /**
     * __toString
     * 
     * @return string
     */
    public function __toString ()
    {
        return (string) $this->getNameOriginal();
    }
    /**
     * @var string
     */
    private $path = '/uploads/project/';
    /**
     * Get path
     *
     * @return string 
     */
    public function getPath ()
    {
        return $this->path;
    }
    /**
     * getAbsolutePath
     *
     * @return null|string
     */
    public function getAbsolutePath ()
    {
        return null === $this->getName() ? null : $this->getUploadRootDir() . '/' . $this->getName();
    }
    /**
     * fileExists
     *
     * @return null|string
     */
    public function fileExists ()
    {
        return null === $this->getName() ? null : file_exists($this->getAbsolutePath());
    }
    /**
     * getWebPath
     *
     * @return null|string
     */
    public function getWebPath ()
    {
        return null === $this->getName() ? null : $this->getUploadDir() . '/' . $this->getName();
    }
    /**
     * getUploadRootDir
     *
     * @return string
     */
    protected function getUploadRootDir ()
    {
        $dir = __DIR__ . '/../../../../web/' . $this->getUploadDir();

        return $dir;
    }
    /**
     * getUploadDir
     *
     * @return string
     */
    protected function getUploadDir ()
    {
        return $this->getPath() . $this->getId() . '/';
    }

    private $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile (UploadedFile $file = null)
    {
        $this->file = $file;
    }
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile ()
    {
        return $this->file;
    }
    /**
     * upload
     */
    public function upload ()
    {
        if (null === $this->getFile()) {
            return;
        }

        $fileExtension = $this->getFile()->getClientOriginalExtension();
        $this->setNameOriginal($this->getFile()->getClientOriginalName());

        $filename = uniqid() . '.' . $fileExtension;

        $uploadDir = $this->getUploadRootDir();

        $this->getFile()->move($uploadDir, $filename);

        $this->setName($filename);
        $this->setCreateDatetime(new \DateTime());

        $this->file = null;
    }
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
    private $shortText;

    /**
     * @var \DateTime
     */
    private $createDatetime;

    /**
     * @var \DateTime
     */
    private $deletedDatetime;

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
     * Set name
     *
     * @param string $name
     * @return File
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
     * Set shortText
     *
     * @param string $shortText
     * @return File
     */
    public function setShortText($shortText)
    {
        $this->shortText = $shortText;
    
        return $this;
    }

    /**
     * Get shortText
     *
     * @return string 
     */
    public function getShortText()
    {
        return $this->shortText;
    }

    /**
     * Set createDatetime
     *
     * @param \DateTime $createDatetime
     * @return File
     */
    public function setCreateDatetime($createDatetime)
    {
        $this->createDatetime = $createDatetime;
    
        return $this;
    }

    /**
     * Get createDatetime
     *
     * @return \DateTime 
     */
    public function getCreateDatetime()
    {
        return $this->createDatetime;
    }

    /**
     * Set deletedDatetime
     *
     * @param \DateTime $deletedDatetime
     * @return File
     */
    public function setDeletedDatetime($deletedDatetime)
    {
        $this->deletedDatetime = $deletedDatetime;
    
        return $this;
    }

    /**
     * Get deletedDatetime
     *
     * @return \DateTime 
     */
    public function getDeletedDatetime()
    {
        return $this->deletedDatetime;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * @return File
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
     * @var string
     */
    private $nameOriginal;


    /**
     * Set nameOriginal
     *
     * @param string $nameOriginal
     * @return File
     */
    public function setNameOriginal($nameOriginal)
    {
        $this->nameOriginal = $nameOriginal;
    
        return $this;
    }

    /**
     * Get nameOriginal
     *
     * @return string 
     */
    public function getNameOriginal()
    {
        return $this->nameOriginal;
    }
}