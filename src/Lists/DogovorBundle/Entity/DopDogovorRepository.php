<?php

namespace Lists\DogovorBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * DopDogovorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DopDogovorRepository extends EntityRepository
{
    /**
     * Return all dop dogovor depending on dogovor id
     *
     * @param int $dogovorId
     * @param int $id
     *
     * @return Query
     */
    public function getAllByDogovorIdQuery($dogovorId, $id = null)
    {
        $query = $this->createQueryBuilder('dd')
            ->select('dd.id as id')
            ->addSelect('dd.number as number')
            ->addSelect('dd.createDateTime')
            ->addSelect('dd.subject as subject')
            ->addSelect('dd.dogovorId as dogovorId')
            ->addSelect('dd.dopDogovorType as dopDogovorType')
            ->addSelect('dd.startdatetime as startdatetime')
            ->addSelect('dd.activedatetime as activedatetime')
            ->addSelect('dd.isActive as dopDogovorIsActive')
            ->addSelect('dd.filepath as filepath')
            ->addSelect('dd.total as total')
            ->addSelect("CONCAT(CONCAT(creator.lastName, ' '), creator.firstName) as creatorFullName")
            ->addSelect("CONCAT(CONCAT(saller.lastName, ' '), saller.firstName) as sallerFullName")
            ->addSelect(
                '(
                SELECT
                    COUNT(ddd.id) as countId
                FROM
                    ListsDogovorBundle:DogovorDepartment ddd
                WHERE
                    ddd.dopDogovorId = dd.id
                ) as departmentCount'
            )
            ->leftJoin('dd.user', 'creator')
            ->leftJoin('dd.saller', 'saller');

        if ($dogovorId) {
            $query
                ->where('dd.dogovorId = :dogovorId')
                ->setParameter(':dogovorId', $dogovorId);
        }

        if ($id) {
            $query
                ->andWhere('dd.id = :id')
                ->setParameter(':id', $id);
        }

        return $query->getQuery();
    }

    /**
     * Returns Query for form select depending on dogovorId
     *
     * @param int $dogovorId
     *
     * @return Query $query
     */
    public function getDopDogovorQueryByDogovorId($dogovorId)
    {
        return  $this->createQueryBuilder('dd')
            ->where('dd.dogovorId = :dogovorId')
            ->setParameter(':dogovorId', $dogovorId);

    }

    /**
     * Returns dogovor collection depending on filter
     *
     * @param mixed[] $filters
     * @param int     $id
     *
     * @return Query
     */
    public function getAllForDopDogovorQuery($filters, $id = null)
    {
        /** @var \Doctrine\ORM\QueryBuilder $sql*/
        $sql = $this->createQueryBuilder('dd');

        /** @var \Doctrine\ORM\QueryBuilder $sqlCount */
        $sqlCount = $this->createQueryBuilder('dd');

        $this->processSelect($sql);
        $this->processCount($sqlCount);

        $this->processBaseQuery($sql);
        $this->processBaseQuery($sqlCount);

        if ($id) {
            $this->processIdQuery($sql, $id);
            $this->processIdQuery($sqlCount, $id);
        } else {
            $this->processFilters($sql, $filters);
            $this->processFilters($sqlCount, $filters);
        }

        $this->processOrdering($sql);

        $query = $sql->getQuery();

        $count = $sqlCount->getQuery()->getSingleScalarResult();

        $query->setHint('knp_paginator.count', $count);

        return $query;
    }
    /**
     * Processes sql query. adding select
     *
     * @param QueryBuilder $sql
     */
    public function processSelect($sql)
    {
        $sql
            ->select('dd.id')
            ->addSelect('dd.number as number')
            ->addSelect('dd.subject as subject')
            ->addSelect('dd.dogovorId as dogovorId')
            ->addSelect('dd.dopDogovorType as dopDogovorType')
            ->addSelect('dd.startdatetime as startdatetime')
            ->addSelect('dd.activedatetime as activedatetime')
            ->addSelect('dd.isActive as dopDogovorIsActive')
            ->addSelect('dd.filepath as filepath')
            ->addSelect('dd.total as total')
            ->addSelect("CONCAT(CONCAT(creator.lastName, ' '), creator.firstName) as creatorFullName")
            ->addSelect("CONCAT(CONCAT(saller.lastName, ' '), saller.firstName) as sallerFullName")
            ->addSelect(
                '(
                SELECT
                    COUNT(ddd.id)
                FROM
                    ListsDogovorBundle:DogovorDepartment ddd
                WHERE
                    ddd.dopDogovorId = dd.id
                ) as departmentCount'
            );
    }
    /**
     * Processes sql query. adding select
     *
     * @param QueryBuilder $sql
     */
    public function processCount($sql)
    {
        $sql
            ->select('COUNT(dd.id) as dopcount');

    }

    /**
     * Processes sql query. adding base query
     *
     * @param QueryBuilder $sql
     */
    public function processBaseQuery($sql)
    {
        $sql
            ->leftJoin('dd.dogovor', 'dogovor')
            ->leftJoin('dd.user', 'creator')
            ->leftJoin('dd.saller', 'saller');
    }

    /**
     * Processes sql query. adding id query
     *
     * @param QueryBuilder $sql
     * @param int          $id
     */
    public function processIdQuery($sql, $id)
    {
        $sql
            ->andWhere('dd.id = :id')
            ->setParameter(':id', $id);
    }

    /**
     * Processes sql query depending on filters
     *
     * @param QueryBuilder $sql
     * @param mixed[]      $filters
     */
    public function processFilters(QueryBuilder $sql, $filters)
    {
        if (sizeof($filters)) {
            foreach ($filters as $key => $value) {
                if (!$value) {
                    continue;
                }
                switch ($key) {
                    case 'dogovorType':
                        $ids = explode(',', $value);
                        $sql
                            ->andWhere("dogovor.dogovorTypeId in (:dogovorTypeId)")
                            ->setParameter(':dogovorTypeId', $ids);
                        break;
                    case 'dateRange':
                        $dateArr = explode('-', $value);
                        $dateStart = new \DateTime(str_replace('.', '-', $dateArr[0]));
                        $dateStop = new \DateTime(str_replace('.', '-', $dateArr[1]));
                        $sql
                            ->andWhere("dd.createDateTime BETWEEN :datestart AND :datestop")
                            ->setParameter(':datestart', $dateStart)
                            ->setParameter(':datestop', $dateStop);
                        break;
                }
            }
        }
    }
    /**
     * Processes sql query. adding users query
     *
     * @param QueryBuilder $sql
     */
    public function processOrdering($sql)
    {
        $sql
            ->orderBy('dd.number', 'ASC');
    }
}
