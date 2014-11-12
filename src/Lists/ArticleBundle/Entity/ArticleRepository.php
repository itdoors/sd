<?php
namespace Lists\ArticleBundle\Entity;

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
        return $res->select('a.id')
            ->addSelect('a.title')
            ->addSelect('a.datePublick')
            ->addSelect('a.dateUnpublick')
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
    public function countPage(QueryBuilder $res)
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
        return $res->leftJoin('a.user', 'u')->leftJoin('a.ration', 'r');
    }

    /** Returns results for interval future invoice
     *
     * @param QueryBuilder $res Description
     * 
     * @return QueryBuilder
     */
    public function whereHistoryPage(QueryBuilder $res)
    {
        return $res->where('a.datePublick < :date')
            ->andWhere('a.dateUnpublick > :date or a.dateUnpublick is NULL')
            ->andWhere('a.type = :type')
            ->setParameter(':type', 'history')
            ->setParameter(':date', date('Y-m-d H:i:s'));
    }

    /**
     * Returns results for interval future invoice
     * 
     * @return array
     */
    public function getHistory()
    {
        $sql = $this->createQueryBuilder('a');
        $count = $this->createQueryBuilder('a');

        $this->selectHistoryPage($sql);
        $this->countPage($count);

        $this->joinHistoryPage($sql);

        $this->whereHistoryPage($sql);
        $this->whereHistoryPage($count);

        return array(
            'articles' => $sql->orderBy('a.datePublick', 'Desc')->getQuery(),
            'count' => $count->getQuery()->getSingleScalarResult()
        );
    }

    /** Returns results for interval future invoice
     *
     * @param QueryBuilder $res Description
     * @param User         $user
     *
     * @return QueryBuilder
     */
    private function selectBlogPage(QueryBuilder $res, User $user)
    {
        $subQueryCase = $res->expr()->andx($res->expr()->eq('n.news', 'a.id'));

        return $res->select('a.id')
            ->addSelect('a.title')
            ->addSelect('a.datePublick')
            ->addSelect('a.dateUnpublick')
            ->addSelect('u.firstName')
            ->addSelect('u.lastName')
            ->addSelect('u.middleName')
            ->leftJoin('Lists\ArticleBundle\Entity\NewsFosUser', 'n', 'WITH', $subQueryCase)
            ->where('a.datePublick < :date')
            ->andWhere('a.dateUnpublick > :date or a.dateUnpublick is NULL')
            ->andWhere('a.type = :type')
            ->andWhere('n.user = :id')
            ->setParameter(':type', 'blog')
            ->setParameter(':id', $user->getId())
            ->setParameter(':date', date('Y-m-d H:i:s'));
    }

    /**
     * Returns new blog articles for current user
     * 
     * @param User $user
     *
     * @return array
     */
    public function getBlog($user)
    {
        $sql = $this->createQueryBuilder('a');
        $count = $this->createQueryBuilder('a');

        $this->selectBlogPage($sql, $user);
        $this->countPage($count);

        $this->joinHistoryPage($sql);
        $this->whereHistoryPage($count);

        return array(
            'articles' => $sql->orderBy('a.datePublick', 'Desc')
                ->getQuery()
                ->getArrayResult(),
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

        return $sql->addSelect('a.text')
            ->where('a.id = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->getSingleResult();
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

        $subQueryCase = $sql->expr()->andx(
            $sql->expr()->eq('v.modelId', 'a.id'),
            $sql->expr()->eq('v.modelName', ':text')
        );

        return $sql->select('v.id')
            ->addSelect('v.value')
            ->addSelect('v.dateCreate as date')
            ->innerJoin('Lists\ArticleBundle\Entity\Vote', 'v', 'WITH', $subQueryCase)
            ->where('a.id = :id')
            ->andWhere('v.userId = :user')
            ->setParameter(':text', 'article')
            ->setParameter(':id', $id)
            ->setParameter(':user', $userId)
            ->getQuery()
            ->getScalarResult();
    }

    /**
     * Returns results for interval future invoice
     *
     * @param QueryBuilder $res Description
     * 
     * @return QueryBuilder
     */
    public function selectDecisionPage(QueryBuilder $res)
    {
        return $res->select('a.id')
            ->addSelect('a.title')
            ->addSelect('a.datePublick')
            ->addSelect('a.dateUnpublick')
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
    public function joinDecisionPage(QueryBuilder $res)
    {
        return $res->leftJoin('a.user', 'u')->leftJoin('a.ration', 'r');
    }

    /** Returns results for interval future invoice
     *
     * @param QueryBuilder $res
     * @param integer      $userId
     * 
     * @return QueryBuilder
     */
    public function joinInnerDecisionPage(QueryBuilder $res, $userId)
    {
        $subQueryCase = $res->expr()->andx(
            $res->expr()->eq('v.modelId', 'a.id'),
            $res->expr()->eq('v.modelName', ':text')
        );
        $subQueryCasef = $res->expr()->andx(
            $res->expr()->eq('vf.modelId', 'a.id'),
            $res->expr()->eq('vf.modelName', ':text')
        );

        return $res->leftJoin('Lists\ArticleBundle\Entity\Vote', 'v', 'WITH', $subQueryCase)
            ->leftJoin('Lists\ArticleBundle\Entity\Vote', 'vf', 'WITH', $subQueryCasef)
            ->andwhere('v.userId = :user')
            ->setParameter(':text', 'article')
            ->setParameter(':user', $userId);
    }

    /** Returns results for interval future invoice
     *
     * @param QueryBuilder $res Description
     * 
     * @return QueryBuilder
     */
    public function whereDecisionPage(QueryBuilder $res)
    {
        return $res->andwhere('a.datePublick < :date')
            ->andWhere('a.type = :type')
            ->setParameter(':type', 'decision')
            ->setParameter(':date', date('Y-m-d H:i:s'));
    }

    /**
     * Returns results for interval future invoice
     * 
     * @param integer $userId User.id
     * 
     * @return array
     */
    public function getDecision($userId)
    {
        $sql = $this->createQueryBuilder('a');
        $count = $this->createQueryBuilder('a');

        $this->selectDecisionPage($sql);
        $this->countPage($count);

        $this->joinDecisionPage($sql);

        if ($userId) {
            $this->joinInnerDecisionPage($sql, $userId);
            $this->joinInnerDecisionPage($count, $userId);
        }
        $this->whereDecisionPage($sql);
        $this->whereDecisionPage($count);

        return array(
            'articles' => $sql->orderBy('a.datePublick', 'Desc')->getQuery(),
            'count' => $count->getQuery()->getSingleScalarResult()
        );
    }

    /**
     * Returns results for interval future invoice
     * 
     * @param integer $userId User.id
     * 
     * @return array
     */
    public function getDecisionForCalendar($userId)
    {
        $sql = $this->createQueryBuilder('a');

        $sql->select('a.id')
            ->addSelect('a.title')
            ->addSelect('a.dateUnpublick');
        if ($userId) {
            $this->joinInnerDecisionPage($sql, $userId);
            $sql->andWhere('v.value is NULL');
        }
        $sql->andWhere('a.dateUnpublick > :date')
            ->andWhere('a.type = :type')
            ->setParameter(':type', 'decision')
            ->setParameter(':date', date('Y-m-d H:i:s'));

        return $sql->orderBy('a.datePublick', 'Desc')
            ->getQuery()
            ->getResult();
    }
}
