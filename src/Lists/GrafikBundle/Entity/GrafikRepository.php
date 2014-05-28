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
    public function getCoworkerHoursMonthInfo($year, $month, $idDepartment, $idCoworker) {
        $result = $this->createQueryBuilder('t')
            ->select('t.total as total')
            ->addSelect('t.day as day')
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

    public function getMonthsFromDepartment($idDepartment) {
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

    public function getYearsFromDepartment($idDepartment) {
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
}