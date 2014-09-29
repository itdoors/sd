<?php

namespace SD\CalendarBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * HolidayRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HolidayRepository extends EntityRepository
{
    /**
     * Returns results for interval future invoice
     *
     * @param QueryBuilder $res Description
     * 
     * @return QueryBuilder
     */
    public function selectCount (QueryBuilder $res)
    {
        $res->select('COUNT(h.id)');

        return $res;
    }
    /**
     * Returns results for interval future invoice
     *
     * @param QueryBuilder $res Description
     * 
     * @return QueryBuilder
     */
    public function select (QueryBuilder $res)
    {
        $res->select('h');

        return $res;
    }
    /**
     * Returns results for interval future holidays
     *
     * @return mixed[]
     */
    public function getList ()
    {
        $res = $this->createQueryBuilder('h');
        $resCount = $this->createQueryBuilder('h');

        /** select */
        $this->select($res);
        $this->selectCount($resCount);

        return array (
            'entities' => $res->getQuery(),
            'count' => $resCount->getQuery()->getSingleScalarResult()
        );
    }
    /**
     * getListForDate
     * 
     * @param integer $startTimestamp
     * @param integer $endTimestamp
     * 
     * @return mixed[]
     */
    public function getListForDate ($startTimestamp, $endTimestamp)
    {
        $res = $this->createQueryBuilder('h');

        /** select */
        $this->select($res);
        /** where */
        if (date('Y', $startTimestamp) == date('Y', $endTimestamp)) {
            $res->andWhere('dayofyear(h.date) >= :dayofyearStart')
                ->andWhere('dayofyear(h.date) <= :dayofyearStop');
        } else {
            $res->andWhere('dayofyear(h.date) >= :dayofyearStart) or (dayofyear(u.birthday) <= :dayofyearStop)');
        }
        $res->setParameter(':dayofyearStart', date('z', $startTimestamp))
            ->setParameter(':dayofyearStop', date('z', $endTimestamp));

        return $res->getQuery()->getResult();
    }
}