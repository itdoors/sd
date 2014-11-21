<?php

namespace Lists\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NewsCompanystructure
 */
class NewsCompanystructure
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $vote;

    /**
     * @var \Lists\ArticleBundle\Entity\Article
     */
    private $news;

    /**
     * @var \Lists\CompanystructureBundle\Entity\Companystructure
     */
    private $companystructure;


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
     * Set vote
     *
     * @param boolean $vote
     * 
     * @return NewsCompanystructure
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

    /**
     * Set news
     *
     * @param \Lists\ArticleBundle\Entity\Article $news
     * 
     * @return NewsCompanystructure
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
     * Set companystructure
     *
     * @param \Lists\CompanystructureBundle\Entity\Companystructure $companystructure
     * 
     * @return NewsCompanystructure
     */
    public function setCompanystructure(\Lists\CompanystructureBundle\Entity\Companystructure $companystructure = null)
    {
        $this->companystructure = $companystructure;

        return $this;
    }

    /**
     * Get companystructure
     *
     * @return \Lists\CompanystructureBundle\Entity\Companystructure 
     */
    public function getCompanystructure()
    {
        return $this->companystructure;
    }
}
