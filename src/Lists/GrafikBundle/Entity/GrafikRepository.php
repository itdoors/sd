<?php

namespace Lists\GrafikBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * GrafikRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GrafikRepository extends EntityRepository
{
    /**
     * @param integer $year
     * @param integer $month
     * @param integer $idDepartment
     * @param integer $idCoworker
     *
     * @return mixed[]
     */
    public function getCoworkerHoursMonthInfo($year, $month, $idDepartment, $idCoworker)
    {
        $result = $this->createQueryBuilder('t')
            ->select('t.total as total')
            ->addSelect('t.day as day')
            ->addSelect('t.isSick as isSick')
            ->addSelect('t.isSkip as isSkip')
            ->addSelect('t.isFired as isFired')
            ->addSelect('t.isVacation as isVacation')
            ->addSelect('t.totalNotOfficially as totalNotOfficially')
            ->leftJoin('t.department', 'd')
            ->leftJoin('t.departmentPeople', 'dp')
            ->andWhere('t.month = :month')
            ->setParameter('month', $month)
            ->andWhere('t.year = :year')
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
     * @param integer $idDepartment
     *
     * @return array
     */
    public function getMonthsFromDepartment($idDepartment)
    {
        $result = $this->createQueryBuilder('g')
            ->select('g.month')
            ->leftJoin('g.department', 'd')
            ->andWhere('d.id = :idDepartment')
            ->setParameter(':idDepartment', $idDepartment)
            ->groupBy('g.month')
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @param integer $idDepartment
     *
     * @return array
     */
    public function getYearsFromDepartment($idDepartment)
    {
        $result = $this->createQueryBuilder('g')
            ->select('g.year')
            ->leftJoin('g.department', 'd')
            ->andWhere('d.id = :idDepartment')
            ->setParameter(':idDepartment', $idDepartment)
            ->groupBy('g.year')
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @param integer $day
     * @param integer $year
     * @param integer $month
     * @param integer $idDepartment
     * @param integer $idCoworker
     *
     * @return array
     */
    public function getCoworkerHoursDayInfo($day, $year, $month, $idDepartment, $idCoworker)
    {
        $result = $this->createQueryBuilder('t')
            ->select('t.total as total')
            ->addSelect('t.totalNotOfficially as totalNotOfficially')
            ->addSelect('t.isSick as isSick')
            ->addSelect('t.isSkip as isSkip')
            ->addSelect('t.isFired as isFired')
            ->addSelect('t.isVacation as isVacation')
            ->leftJoin('t.department', 'd')
            ->leftJoin('t.departmentPeople', 'dp')
            ->andWhere('t.month = :month')
            ->setParameter(':month', $month)
            ->andWhere('t.day = :day')
            ->setParameter(':day', $day)
            ->andWhere('t.year = :year')
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
     * @param integer $idCoworker
     * @param integer $idDepartment
     *
     * @return mixed
     */
    public function isCoworkerFired($idCoworker, $idDepartment)
    {
        $result = $this->createQueryBuilder('g')
            ->select('COUNT(g.day)')
            ->leftJoin('g.department', 'd')
            ->leftJoin('g.departmentPeople', 'dp')
            ->andWhere('d.id = :idDepartment')
            ->setParameter(':idDepartment', $idDepartment)
            ->andWhere('dp.id = :idCoworker')
            ->setParameter(':idCoworker', $idCoworker)
            ->andWhere('g.isFired = true')
            ->getQuery()
            ->getSingleScalarResult();

        if ($result) {
            return true;
        }

        return false;
    }
}
