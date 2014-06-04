<?php

namespace Lists\GrafikBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * GrafikTimeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GrafikTimeRepository extends EntityRepository
{
    /**
     * @param integer $year
     * @param integer $month
     * @param integer $day
     * @param integer $idDepartment
     * @param integer $idCoworker
     *
     * @return array
     */
    public function getCoworkerHoursDayInfo($year, $month, $day, $idDepartment, $idCoworker)
    {
        $result = $this->createQueryBuilder('gt')
            ->leftJoin('gt.department', 'd')
            ->leftJoin('gt.departmentPeople', 'dp')
            ->andWhere('gt.month = :month')
            ->setParameter('month', $month)
            ->andWhere('gt.day = :day')
            ->setParameter('day', $day)
            ->andWhere('gt.year = :year')
            ->setParameter(':year', $year)
            ->andWhere('d.id = :idDepartment')
            ->setParameter(':idDepartment', $idDepartment)
            ->andWhere('dp.id = :idCoworker')
            ->setParameter(':idCoworker', $idCoworker)
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @param integer $year
     * @param integer $month
     * @param integer $day
     * @param integer $idDepartment
     * @param integer $idCoworker
     * @param string  $fromTime
     * @param string  $toTime
     * @param integer $idTimeGrafik
     *
     * @return \Doctrine\ORM\QueryBuilder|mixed
     */
    public function havingSubtime($year, $month, $day, $idDepartment, $idCoworker, $fromTime, $toTime, $idTimeGrafik)
    {
        $result = $this->createQueryBuilder('gt')
            ->select('COUNT (gt.id)')
            ->leftJoin('gt.department', 'd')
            ->leftJoin('gt.departmentPeople', 'dp')
            ->andWhere('gt.month = :month')
            ->setParameter('month', $month)
            ->andWhere('gt.day = :day')
            ->setParameter('day', $day)
            ->andWhere('gt.year = :year')
            ->setParameter(':year', $year)
            ->andWhere('d.id = :idDepartment')
            ->setParameter(':idDepartment', $idDepartment)
            ->andWhere('dp.id = :idCoworker')
            ->setParameter(':idCoworker', $idCoworker)
            ->andWhere(
                '(gt.fromTime > :startTime AND gt.fromTime < :endTime)
                OR (gt.toTime > :startTime AND gt.toTime < :endTime)
                OR (:startTime > gt.fromTime AND :startTime < gt.toTime)
                OR (:endTime > gt.fromTime AND :endTime < gt.toTime)
                OR (gt.fromTime = :startTime AND gt.toTime = :endTime)'
            )
            ->setParameter(':startTime', $fromTime)
            ->setParameter(':endTime', $toTime);

        if ($idTimeGrafik>0) {
            $result = $result->andWhere('gt.id != :idTimeGrafik')
            ->setParameter(':idTimeGrafik', $idTimeGrafik);
        }

        $result = $result->getQuery()
        ->getSingleScalarResult();

        return $result;
    }
}
