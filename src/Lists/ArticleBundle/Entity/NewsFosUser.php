<?php

namespace Lists\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NewsFosUser
 */
class NewsFosUser
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $viewed;

    /**
     * @var \Lists\ArticleBundle\Entity\Article
     */
    private $news;

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
     * Set viewed
     *
     * @param \DateTime $viewed
     * @return NewsFosUser
     */
    public function setViewed($viewed)
    {
        $this->viewed = $viewed;
    
        return $this;
    }

    /**
     * Get viewed
     *
     * @return \DateTime 
     */
    public function getViewed()
    {
        return $this->viewed;
    }

    /**
     * Set news
     *
     * @param \Lists\ArticleBundle\Entity\Article $news
     * @return NewsFosUser
     */
    public function setNews(\Lists\ArticleBundle\Entity\Article $news = null)
    {
        $this->news = $news;
    
        return $this;
    }

    /**
     * Get news
     *
     * @return \Lists\ArticleBundle\Entity\Article 
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * @return NewsFosUser
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
}
