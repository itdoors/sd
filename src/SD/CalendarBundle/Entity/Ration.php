<?php

namespace SD\CalendarBundle\Entity;

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
     * @var \SD\CalendarBundle\Entity\Article
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
     * @param \double $value
     * @return Ration
     */
    public function setValue(\double $value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return \double 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set article
     *
     * @param \SD\CalendarBundle\Entity\Article $article
     * @return Ration
     */
    public function setArticle(\SD\CalendarBundle\Entity\Article $article = null)
    {
        $this->article = $article;
    
        return $this;
    }

    /**
     * Get article
     *
     * @return \SD\CalendarBundle\Entity\Article 
     */
    public function getArticle()
    {
        return $this->article;
    }
}
