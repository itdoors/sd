<?php

namespace Lists\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NewsRole
 */
class NewsRole
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var Lists\ArticleBundle\Entity\Article
     */
    private $news;

    /**
     * @var SD\UserBundle\Entity\Group
     */
    private $roles;


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
     * Set news
     *
     * @param integer $newsId
     * @return NewsRole
     */
    public function setNews($news)
    {
        $this->news = $news;
    
        return $this;
    }

    /**
     * Get news
     *
     * @return Lists\ArticleBundle\Entity\Article 
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Set role
     *
     * @param integer $role
     * @return NewsRole
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    
        return $this;
    }

    /**
     * Get roles
     *
     * @return SD\UserBundle\Entity\Group 
     */
    public function getRoles()
    {
        return $this->roles;
    }
}
