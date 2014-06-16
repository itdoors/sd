<?php

namespace SD\CalendarBundle\Entity;

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
    private $datePablick;

    /**
     * @var \DateTime
     */
    private $dateUnpablick;

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
     * Set datePablick
     *
     * @param \DateTime $datePablick
     * 
     * @return Article
     */
    public function setDatePablick($datePablick)
    {
        $this->datePablick = $datePablick;

        return $this;
    }

    /**
     * Get datePablick
     *
     * @return \DateTime 
     */
    public function getDatePablick()
    {
        return $this->datePablick;
    }

    /**
     * Set dateUnpablick
     *
     * @param \DateTime $dateUnpablick
     * 
     * @return Article
     */
    public function setDateUnpablick($dateUnpablick)
    {
        $this->dateUnpablick = $dateUnpablick;

        return $this;
    }

    /**
     * Get dateUnpablick
     *
     * @return \DateTime 
     */
    public function getDateUnpablick()
    {
        return $this->dateUnpablick;
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
}
