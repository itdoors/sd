<?php

namespace SD\CalendarBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use SD\UserBundle\Entity\User;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{

    /**
     * Returns results for interval future invoice
     *
     * @param QueryBuilder $res Description
     * 
     * @return QueryBuilder
     */
    public function selectHistoryPage(QueryBuilder $res)
    {
        return $res
                ->select('a.id')
                ->addSelect('a.title')
                ->addSelect('a.datePublick')
                ->addSelect('u.firstName')
                ->addSelect('u.lastName')
                ->addSelect('u.middleName')
                ->addSelect('r.value');
    }

    /** Returns results for interval future invoice
     *
     * @param QueryBuilder $res Description
     * 
     * @return QueryBuilder
     */
    public function countHistoryPage(QueryBuilder $res)
    {
        return $res->select('COUNT(a.id)');
    }

    /** Returns results for interval future invoice
     *
     * @param QueryBuilder $res Description
     * 
     * @return QueryBuilder
     */
    public function joinHistoryPage(QueryBuilder $res)
    {
        return $res
                ->leftJoin('a.user', 'u')
                ->leftJoin('a.ration', 'r');
    }

    /** Returns results for interval future invoice
     *
     * @param QueryBuilder $res Description
     * 
     * @return QueryBuilder
     */
    public function whereHistoryPage(QueryBuilder $res)
    {
        return $res
                ->where('a.datePublick < :date')
                ->andWhere('a.dateUnpublick > :date or a.dateUnpublick is NULL')
                ->setParameter(':date', date('Y-m-d H:i:s'));
    }

    /**
     * Returns results for interval future invoice

     * @return array
     */
    public function getArticles()
    {
        $sql = $this->createQueryBuilder('a');
        $count = $this->createQueryBuilder('a');

        $this->selectHistoryPage($sql);
        $this->countHistoryPage($count);

        $this->joinHistoryPage($sql);

        $this->whereHistoryPage($sql);
        $this->whereHistoryPage($count);

        return array(
            'articles' => $sql->orderBy('a.datePublick', 'Desc')->getQuery(),
            'count' => $count->getQuery()->getSingleScalarResult()
        );
    }

    /**
     * Returns results for interval future invoice
     * 
     * @param integer $id Article.id
     * 
     * @return array
     */
    public function getArticle($id)
    {
        $sql = $this->createQueryBuilder('a');

        $this->selectHistoryPage($sql);

        $this->joinHistoryPage($sql);

        return $sql
                ->addSelect('a.text')
                ->where('a.id = :id')
                ->setParameter(':id', $id)
                ->getQuery()->getSingleResult();
    }

    /**
     * Returns results for interval future invoice
     * 
     * @param integer $id     Article.id
     * @param integer $userId User.id  
     * 
     * @return array
     */
    public function getVote($id, $userId)
    {
        $sql = $this->createQueryBuilder('a');

        $subQueryCase = $sql->expr()
            ->andx(
                $sql->expr()->eq('v.modelId', 'a.id'),
                $sql->expr()->eq('v.modelName', ':text')
            );

        return $sql
                ->addSelect('v.value')
                ->leftJoin('SD\CalendarBundle\Entity\Vote', 'v', 'WITH', $subQueryCase)
                ->where('a.id = :id')
                ->andWhere('v.userId = :user')
                ->setParameter(':text', 'article')
                ->setParameter(':id', $id)
                ->setParameter(':user', $userId)
                ->getQuery()
                ->getScalarResult();
    }
}
