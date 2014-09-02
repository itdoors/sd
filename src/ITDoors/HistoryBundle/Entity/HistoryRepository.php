<?php

namespace ITDoors\HistoryBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * HistoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HistoryRepository extends EntityRepository
{
    /**
     * Processes sql query. adding select
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processCount ($sql)
    {
        $sql->select('COUNT(DISTINCT h.id) as hcount');
    }
    /**
     * Returns results for interval future invoice
     *
     * @param QueryBuilder $res Description
     * 
     * @return QueryBuilder
     */
    public function selectHistory (QueryBuilder $res)
    {
        $res
            ->select('h.value')
            ->addSelect('h.oldValue')
            ->addSelect('h.action')
            ->addSelect('h.fieldName')
            ->addSelect('h.createdatetime')
            ->addSelect('u.lastName')
            ->addSelect('u.firstName');
    }
    /**
     * Returns results for interval future invoice
     * 
     * @param QueryBuilder $res Description
     * 
     * @return QueryBuilder
     */
    public function joinHistory (QueryBuilder $res)
    {
        $res->leftJoin('h.user', 'u');

        return $res;
    }
    /**
     * Returns results for interval future invoice
     * 
     * @param QueryBuilder  $res
     * @param string        $modelName
     * @param integer       $modelId
     * 
     * @return QueryBuilder
     */
    public function whereHistory (QueryBuilder $res, $modelName, $modelId)
    {

        $res
            ->andWhere("h.modelName = :modelName")
            ->setParameter(':modelName', $modelName)
            ->andWhere("h.modelId = :modelId")
            ->setParameter(':modelId', $modelId);

        return $res;
    }
    /**
     * Processes sql query depending on filters
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     * @param mixed[]                    $filters
     */
    public function processFilters (\Doctrine\ORM\QueryBuilder $sql, $filters)
    {
        if (sizeof($filters)) {

            foreach ($filters as $key => $value) {
                if (!$value) {
                    continue;
                }
                switch ($key) {
                    case 'action':
                        $arr = explode(',', $value);
                        $sql->andWhere("h.action in (:actions)");
                        $sql->setParameter(':actions', $arr);
                        break;
                }
            }
        }
    }
    /**
     * getHistories
     * 
     * @param string  $modelName
     * @param integer $modelId
     * @param string  $filterNamespace
     * @param array   $filters
     * 
     * @return type
     */
    public function getHistories ($modelName, $modelId, $filterNamespace, $filters)
    {
        $res = $this->createQueryBuilder('h');
        $resCount = $this->createQueryBuilder('h');

        /** select */
        $this->selectHistory($res);
        $this->processCount($resCount);
        /** join */
        $this->joinHistory($res);
        $this->joinHistory($resCount);
        /** where */
        $this->whereHistory($res, $modelName, $modelId);
        $this->whereHistory($resCount, $modelName, $modelId);
        /** filters */
        $this->processFilters($res, $filters);
        $this->processFilters($resCount, $filters);

        $res->orderBy('h.createdatetime', 'DESC');

        $query = $res->getQuery();

        $count = $resCount->getQuery()->getSingleScalarResult();

        $query->setHint($filterNamespace . '.count', $count);

        return $query;
    }
}
