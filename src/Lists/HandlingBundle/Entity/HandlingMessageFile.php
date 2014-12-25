<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * HandlingMessageFile
 */
class HandlingMessageFile
{
    /**
     * __construct
     */
    public function __construct ()
    {
        $this->setCreatedate(new \DateTime());
    }
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdate;

    /**
     * @var string
     */
    private $file;

    /**
     * @var \Lists\HandlingBundle\Entity\HandlingMessage
     */
    private $handlingMessage;

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
     * Set createdate
     *
     * @param \DateTime $createdate
     * 
     * @return HandlingMessageFile
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
     * Set file
     *
     * @param string $file
     * 
     * @return HandlingMessageFile
     */
    public function setFile ($file)
    {
        $this->file = $file;

        return $this;
    }
    /**
     * Get file
     *
     * @return string 
     */
    public function getFile ()
    {
        return $this->file;
    }
    /**
     * Set handlingMessage
     *
     * @param \Lists\HandlingBundle\Entity\HandlingMessage $handlingMessage
     * 
     * @return HandlingMessageFile
     */
    public function setHandlingMessage (\Lists\HandlingBundle\Entity\HandlingMessage $handlingMessage = null)
    {
        $this->handlingMessage = $handlingMessage;

        return $this;
    }
    /**
     * Get handlingMessage
     *
     * @return \Lists\HandlingBundle\Entity\HandlingMessage 
     */
    public function getHandlingMessage ()
    {
        return $this->handlingMessage;
    }
    /**
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return
            null === $this->getFile()
            ? null
            : $this->getUploadRootDir() . '/' . $this->getFile();
    }

    /**
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->getFile()
            ? null
            : $this->getUploadDir() . DIRECTORY_SEPARATOR . $this->getFile();
    }

    /**
     * @return null|string
     */
    protected function getUploadRootDir()
    {
        return __DIR__ . '/../../../../web'. DIRECTORY_SEPARATOR . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        $dir = __DIR__ . '/../../../../web/uploads/handling_message';
        if (!is_dir($dir)) {
            mkdir($dir, 0775);
        }
        $dir .= DIRECTORY_SEPARATOR . $this->getHandlingMessage()->getHandling()->getId();
        if (!is_dir($dir)) {
            mkdir($dir, 0775);
        }

        return 'uploads'. DIRECTORY_SEPARATOR
            . 'handling_message'. DIRECTORY_SEPARATOR
            . $this->getHandlingMessage()->getHandling()->getId();
    }

    private $fileTemp;

    /**
     * Sets fileTemp.
     *
     * @param UploadedFile $fileTemp
     */
    public function setFileTemp(UploadedFile $fileTemp = null)
    {
        $this->fileTemp = $fileTemp;
    }

    /**
     * Get fileTemp.
     *
     * @return UploadedFile
     */
    public function getFileTemp()
    {
        return $this->fileTemp;
    }

    /**
     * upload
     */
    public function upload()
    {
        if (null === $this->getFileTemp()) {
            return;
        }
        $fileExtension = $this->getFileTemp()->getClientOriginalExtension();
        $newFileName = md5(microtime());
        $filepath = $newFileName . '.' . $fileExtension;
        $dir = $this->getUploadRootDir();
        $this->getFileTemp()->move($dir, $filepath);
        $this->setFile($filepath);
        $this->fileTemp = null;
    }
    /**
    * Called before entity removal
    *
    * @ORM\PostRemove()
    */
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();
        if (file_exists($file)) {
            unlink($file);
            $this->setFile('');
        }
    }
    /**
     * @ORM\PostRemove
     */
    public function postRemoveFile()
    {
        $this->removeUpload();
    }
}
