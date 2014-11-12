<?php

namespace Lists\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SD\UserBundle\Entity\Group;

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
     * @param Article $news
     * 
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
     * @param array $roles
     * 
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

    /**
     * @var boolean
     */
    private $vote;

    /**
     * Set vote
     *
     * @param boolean $vote
     * 
     * @return NewsRole
     */
    public function setVote($vote)
    {
        $this->vote = $vote;

        return $this;
    }

    /**
     * Get vote
     *
     * @return boolean
     */
    public function getVote()
    {
        return $this->vote;
    }
}
