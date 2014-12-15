<?php

namespace Lists\CoachBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CoachReport
 */
class CoachReport
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $text;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \Lists\CoachBundle\Entity\Action
     */
    private $action;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $author;

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
     * Set title
     *
     * @param string $title
     * 
     * @return CoachReport
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
     * Set text
     *
     * @param string $text
     * 
     * @return CoachReport
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
     * Set created
     *
     * @param \DateTime $created
     * 
     * @return CoachReport
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set action
     *
     * @param \Lists\CoachBundle\Entity\Action $action
     * 
     * @return CoachReport
     */
    public function setAction(\Lists\CoachBundle\Entity\Action $action = null)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return \Lists\CoachBundle\Entity\Action 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set author
     *
     * @param \SD\UserBundle\Entity\User $author
     * 
     * @return CoachReport
     */
    public function setAuthor(\SD\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
