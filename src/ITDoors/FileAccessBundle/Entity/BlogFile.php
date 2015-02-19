<?php

namespace ITDoors\FileAccessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BlogFile
 *
 * @ORM\Entity
 */
class BlogFile extends File
{
    /**
     * @var \SD\ServiceDeskBundle\Entity\Claim
     *
     * @ORM\ManyToOne(targetEntity="Lists\ArticleBundle\Entity\Article", inversedBy="files")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     */
    protected $article;

    protected $path = '/uploads/files/blog/';

    /**
     * Set article
     *
     * @param \Lists\ArticleBundle\Entity\Article $article
     * 
     * @return BlogFile
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
