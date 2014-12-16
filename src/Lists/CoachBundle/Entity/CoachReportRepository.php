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

//         $this->processFilters($sql, $filters);
//         $this->processFilters($sqlCount, $filters);

        $sql->orderBy('c.created', 'DESC');

        $query = array (
            'entity' => $sql->getQuery(),
            'count' => $sqlCount->getQuery()->getSingleScalarResult()
        );

        return $query;
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
                    case 'company':
                        $valueArr = explode(',', $value);
                        $sql->andWhere(
                            "c.id in (:company) or c.id in
                                (
                                    SELECT
                                        cc.id
                                    FROM
                                        ListsCompanystructureBundle:Companystructure cp
                                    LEFT JOIN
                                        ListsCompanystructureBundle:Companystructure cc
                                    WHERE
                                        cp.root = cc.root
                                    AND
                                        cp.lft < cc.lft
                                    AND
                                        cp.rgt > cc.rgt
                                    AND
                                        cp in (:company)
                                )"
                        );
                        $sql->setParameter(':company', $valueArr);
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
