<?php

namespace Lists\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 */
class Article
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $file;

    /**
     * @var string
     */
    private $textShort;

    /**
     * @var string
     */
    private $text;

    /**
     * @var \DateTime
     */
    private $dateCreate;

    /**
     * @var \DateTime
     */
    private $dateUpdate;

    /**
     * @var \DateTime
     */
    private $datePublick;

    /**
     * @var \DateTime
     */
    private $dateUnpublick;

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
     * Set userId
     *
     * @param integer $userId
     * 
     * @return Article
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
     * Set type
     *
     * @param string $type
     * 
     * @return Article
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set title
     *
     * @param string $title
     * 
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set file
     *
     * @param string $file
     *
     * @return Article
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
     * Set textShort
     *
     * @param string $textShort
     * 
     * @return Article
     */
    public function setTextShort($textShort)
    {
        $this->textShort = $textShort;

        return $this;
    }

    /**
     * Get textShort
     *
     * @return string 
     */
    public function getTextShort()
    {
        return $this->textShort;
    }

    /**
     * Set text
     *
     * @param string $text
     * 
     * @return Article
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * 
     * @return Article
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime 
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     * 
     * @return Article
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime 
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set datePublick
     *
     * @param \DateTime $datePublick
     * 
     * @return Article
     */
    public function setDatePublick($datePublick)
    {
        $this->datePublick = $datePublick;

        return $this;
    }

    /**
     * Get datePublick
     *
     * @return \DateTime 
     */
    public function getDatePublick()
    {
        return $this->datePublick;
    }

    /**
     * Set dateUnpublick
     *
     * @param \DateTime $dateUnpublick
     * 
     * @return Article
     */
    public function setDateUnpulick($dateUnpublick)
    {
        $this->dateUnpublick = $dateUnpublick;

        return $this;
    }

    /**
     * Get dateUnpublick
     *
     * @return \DateTime 
     */
    public function getDateUnpublick()
    {
        return $this->dateUnpublick;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * 
     * @return Article
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
     * __toString()
     * 
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getTitle();
    }

    /**
     * @var \Lists\ArticleBundle\Entity\Ration
     */
    private $ration;

    /**
     * Set dateUnpublick
     *
     * @param \DateTime $dateUnpublick
     * 
     * @return Article
     */
    public function setDateUnpublick($dateUnpublick)
    {
        $this->dateUnpublick = $dateUnpublick;

        return $this;
    }

    /**
     * Set ration
     *
     * @param \Lists\ArticleBundle\Entity\Ration $ration
     * 
     * @return Article
     */
    public function setRation(\Lists\ArticleBundle\Entity\Ration $ration = null)
    {
        $this->ration = $ration;

        return $this;
    }

    /**
     * Get ration
     *
     * @return \Lists\ArticleBundle\Entity\Ration 
     */
    public function getRation()
    {
        return $this->ration;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $files;

    /**
     * Add file
     *
     * @param \ITDoors\FileAccessBundle\Entity\BlogFile $file
     *
     * @return Article
     */
    public function addFile(\ITDoors\FileAccessBundle\Entity\BlogFile $file)
    {
        $file->setArticle($this);
        $this->files[] = $file;
    
        return $this;
    }
    
    /**
     * Remove file
     *
     * @param \ITDoors\FileAccessBundle\Entity\BlogFile $file
     */
    public function removeFile(\ITDoors\FileAccessBundle\Entity\BlogFile $file)
    {
        $this->files->removeElement($file);
    }
    
    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }
}
