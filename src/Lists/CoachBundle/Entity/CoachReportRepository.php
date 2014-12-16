<?php

namespace Lists\CoachBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * CoachReportRepository
 */
class CoachReportRepository extends EntityRepository
{
    /**
     * getAll
     * 
     * @param array $filters
     * 
     * @return mixed[]
     */
    public function getAll($filters = [])
    {
        $sql = $this->createQueryBuilder('c');
        $sqlCount = $this->createQueryBuilder('c');

        $sqlCount->select('COUNT(c.id) as reportscount');

        $this->processFilters($sql, $filters);
        $this->processFilters($sqlCount, $filters);

        $sql->orderBy('c.created', 'DESC');

        $query = array (
            'entity' => $sql->getQuery(),
            'count' => $sqlCount->getQuery()->getSingleScalarResult()
        );

        return $query;
    }

    /**
     * getAuthors
     *
     * @param string $searchText
     *
     * @return mixed[]
     */
    public function getAuthors($searchText)
    {
        $authors = $this->_em->createQuery(
            'SELECT DISTINCT IDENTITY(r.author) FROM Lists\CoachBundle\Entity\CoachReport r'
        )->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return $authors;
    }

    /**
    * Processes sql query depending on filters
    *
    * @param QueryBuilder $sql
    * @param mixed[]      $filters
    */
    private function processFilters(QueryBuilder $sql, $filters)
    {
        if (sizeof($filters)) {
            foreach ($filters as $key => $value) {
                if (!$value) {
                    continue;
                }
                switch ($key) {
                    case 'author':
                        $valueArr = explode(',', $value);var_dump($valueArr);die();
                        $sql->andWhere(
                            "c.author in (:authors)
//                             c.id in (:company) or c.id in
//                                 (
//                                     SELECT
//                                         cc.id
//                                     FROM
//                                         ListsCompanystructureBundle:Companystructure cp
//                                     LEFT JOIN
//                                         ListsCompanystructureBundle:Companystructure cc
//                                     WHERE
//                                         cp.root = cc.root
//                                     AND
//                                         cp.lft < cc.lft
//                                     AND
//                                         cp.rgt > cc.rgt
//                                     AND
//                                         cp in (:company)
                                )"
                        );
                        $sql->setParameter(':authors', $valueArr);
                        break;
                    case 'isActive':
                        $sql->andWhere("u.locked = :active");
                        $sql->setParameter(':active', ($value == 'active' ? 0 : 1));
                        break;
                    case 'status':
                        if ($value == 68) {
                            $sql->andWhere("st.id is NULL or st.id = :status");
                        } else {
                            $sql->andWhere("st.id = :status");
                        }
                        $sql->setParameter(':status', $value);
                        break;
                    default:
                        $sql->andWhere("u.id IN (:userIds)");
                        $ids = explode(',', $value);
                        $sql->setParameter(':userIds', $ids);
                }
            }
        }
    }
}
