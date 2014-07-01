<?php

namespace Lists\ArticleBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * VoteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VoteRepository extends EntityRepository
{

    /**
     * Returns results for interval future invoice
     * 
     * @param integer $id Article.id
     * 
     * @return array
     */
    public function getVoteForArticle($id)
    {
        $sql = $this->createQueryBuilder('v');

        $res = $sql
            ->select('SUM(v.value) as sumVote')
            ->addSelect('COUNT(v.id) as countVote')
            ->where('v.modelId = :id')
            ->andwhere('v.modelName = :text')
            ->setParameter(':id', $id)
            ->setParameter(':text', 'article')
            ->getQuery()
            ->getSingleResult();

        return $res;
    }

    /**
     * Returns results for interval future invoice
     * 
     * @param integer $id Article.id
     * 
     * @return array
     */
    public function getVoteForArticleDecision($id)
    {
        $sql = $this->createQueryBuilder('v');

        $res = $sql
            ->select('SUM(CASE when v.value = 0 THEN 1 ELSE 0 END) as count0')
            ->addSelect('SUM(CASE WHEN v.value = 1 THEN 1 ELSE 0 END) as count1')
            ->addSelect('COUNT(v.id) as countVote')
            ->where('v.modelId = :id')
            ->andwhere('v.modelName = :text')
            ->setParameter(':id', $id)
            ->setParameter(':text', 'article')
            ->getQuery()
            ->getSingleResult();

        return $res;
    }

    /**
     * Returns results for interval future invoice
     * 
     * @param integer $id Article.id
     * 
     * @return array
     */
    public function getVoites($id)
    {
        $sql = $this->createQueryBuilder('v');

        $res = $sql
            ->select('v.value')
            ->addSelect('v.dateCreate')
            ->addSelect('u.firstName')
            ->addSelect('u.lastName')
            ->addSelect('u.middleName')
            ->innerjoin('v.user', 'u')
            ->where('v.modelId = :id')
            ->andwhere('v.modelName = :text')
            ->setParameter(':id', $id)
            ->setParameter(':text', 'article')
            ->orderBy('v.dateCreate', 'DESC')
            ->getQuery()
            ->getResult();

        return $res;
    }

    /**
     * Returns results for interval future invoice
     * 
     * @param integer $id Article.id
     * 
     * @return array
     */
    public function getVoitesFor15($id)
    {
        $sql = $this->createQueryBuilder('v');

        $res = $sql
            ->select('v.value')
            ->addSelect('v.dateCreate')
            ->addSelect('u.firstName')
            ->addSelect('u.lastName')
            ->addSelect('u.middleName')
            ->addSelect('u.email')
            ->innerjoin('v.user', 'u')
            ->where('v.modelId = :id')
            ->andwhere('v.modelName = :text')
            ->andwhere('v.value is NULL')
            ->andwhere('v.dateCreate is NULL')
            ->setParameter(':id', (int) $id)
            ->setParameter(':text', 'article')
            ->orderBy('v.dateCreate', 'DESC')
            ->getQuery()
            ->getResult();

        return $res;
    }
}
