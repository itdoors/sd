<?php

namespace Lists\DogovorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * DopDogovor
 */
class DopDogovor
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $dopDogovorType;

    /**
     * @var string
     */
    private $number;

    /**
     * @var \DateTime
     */
    private $startdatetime;

    /**
     * @var \DateTime
     */
    private $activedatetime;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var boolean
     */
    private $isActive;

    /**
     * @var string
     */
    private $filepath;

    /**
     * @var float
     */
    private $total;

    /**
     * @var \Lists\DogovorBundle\Entity\Dogovor
     */
    private $dogovor;

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
     * Set dopDogovorType
     *
     * @param string $dopDogovorType
     * @return DopDogovor
     */
    public function setDopDogovorType($dopDogovorType)
    {
        $this->dopDogovorType = $dopDogovorType;
    
        return $this;
    }

    /**
     * Get dopDogovorType
     *
     * @return string 
     */
    public function getDopDogovorType()
    {
        return $this->dopDogovorType;
    }

    /**
     * Set number
     *
     * @param string $number
     * @return DopDogovor
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

    /**
     * Set startdatetime
     *
     * @param \DateTime $startdatetime
     * @return DopDogovor
     */
    public function setStartdatetime($startdatetime)
    {
        $this->startdatetime = $startdatetime;
    
        return $this;
    }

    /**
     * Get startdatetime
     *
     * @return \DateTime 
     */
    public function getStartdatetime()
    {
        return $this->startdatetime;
    }

    /**
     * Set activedatetime
     *
     * @param \DateTime $activedatetime
     * @return DopDogovor
     */
    public function setActivedatetime($activedatetime)
    {
        $this->activedatetime = $activedatetime;
    
        return $this;
    }

    /**
     * Get activedatetime
     *
     * @return \DateTime 
     */
    public function getActivedatetime()
    {
        return $this->activedatetime;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return DopDogovor
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    
        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return DopDogovor
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set filepath
     *
     * @param string $filepath
     * @return DopDogovor
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
     * Set total
     *
     * @param float $total
     * @return DopDogovor
     */
    public function setTotal($total)
    {
        $this->total = $total;
    
        return $this;
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set dogovor
     *
     * @param \Lists\DogovorBundle\Entity\Dogovor $dogovor
     * @return DopDogovor
     */
    public function setDogovor(\Lists\DogovorBundle\Entity\Dogovor $dogovor = null)
    {
        $this->dogovor = $dogovor;
    
        return $this;
    }

    /**
     * Get dogovor
     *
     * @return \Lists\DogovorBundle\Entity\Dogovor 
     */
    public function getDogovor()
    {
        return $this->dogovor;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * @return DopDogovor
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
     * @var integer
     */
    private $dogovorId;


    /**
     * Set dogovorId
     *
     * @param integer $dogovorId
     * @return DopDogovor
     */
    public function setDogovorId($dogovorId)
    {
        $this->dogovorId = $dogovorId;
    
        return $this;
    }

    /**
     * Get dogovorId
     *
     * @return integer 
     */
    public function getDogovorId()
    {
        return $this->dogovorId;
    }
    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $saller;


    /**
     * Set saller
     *
     * @param \SD\UserBundle\Entity\User $saller
     * @return DopDogovor
     */
    public function setSaller(\SD\UserBundle\Entity\User $saller = null)
    {
        $this->saller = $saller;
    
        return $this;
    }

    /**
     * Get saller
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getSaller()
    {
        return $this->saller;
    }

    public function getAbsolutePath()
    {
        return null === $this->filepath
            ? null
            : $this->getUploadRootDir().'/'.$this->filepath;
    }

    public function getWebPath()
    {
        return null === $this->filepath
            ? null
            : $this->getUploadDir().'/'.$this->filepath;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/dogovor';
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

    /**
     * toString()
     *
     * @return string
     */
    public function __toString()
    {
        return "#" . $this->getId() . ' - ' . $this->getNumber();
    }
}