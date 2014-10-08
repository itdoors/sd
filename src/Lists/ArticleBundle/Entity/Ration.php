<?php

namespace Lists\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ration
 */
class Ration
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $articleId;

    /**
     * @var double
     */
    private $value;

    /**
     * @var Lists\ArticleBundle\Entity\Article
     */
    private $article;

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
     * Set articleId
     *
     * @param integer $articleId
     * 
     * @return Ration
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;

        return $this;
    }

    /**
     * Get articleId
     *
     * @return integer 
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * Set value
     *
     * @param \float $value
     * 
     * @return Ration
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return \float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set article
     *
     * @param \Lists\ArticleBundle\Entity\Article $article
     * 
     * @return Ration
     */
    public function setArticle(\Lists\ArticleBundle\Entity\Article $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \Lists\ArticleBundle\Entity\Article 
     */
    public function getArticle()
    {
        return $this->article;
    }
}
