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
     * @param int $year
     * @param int $month
     * @param int $idDepartment
     * @param int $idCoworker
     * @param int $idReplacement
     *
     * @return array
     */
    public function getCoworkerHoursMonthInfo($year, $month, $idDepartment, $idCoworker, $idReplacement = 0)
    {
        $result = $this->createQueryBuilder('t')
            ->select('t.total as total')
            ->addSelect('t.day as day')
            ->addSelect('t.isSick as isSick')
            ->addSelect('t.isSkip as isSkip')
            ->addSelect('t.isFired as isFired')
            ->addSelect('t.isVacation as isVacation')
            ->addSelect('t.isOwnVacation as isOwnVacation')
            ->addSelect('t.totalNotOfficially as totalNotOfficially')
            ->leftJoin('t.department', 'd')
            ->leftJoin('t.departmentPeople', 'dp')
            ->andWhere('t.month = :month')
            ->setParameter('month', $month)
            ->andWhere('t.year = :year')
            ->setParameter(':year', $year);
        if (is_array($idDepartment)) {
            $result = $result->andWhere('d.id IN (:idDepartment)')
                ->setParameter(':idDepartment', $idDepartment);
        } else {
            $result = $result->andWhere('d.id = :idDepartment')
                ->setParameter(':idDepartment', $idDepartment);
        }
        $result = $result->andWhere('dp.id = :idCoworker')
            ->setParameter(':idCoworker', $idCoworker)
            ->andWhere('t.departmentPeopleReplacement = :idReplacement')
            ->setParameter(':idReplacement', $idReplacement)
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
     * @param int $day
     * @param int $year
     * @param int $month
     * @param int $idDepartment
     * @param int $idCoworker
     * @param int $idReplacement
     *
     * @return array
     */
    public function getCoworkerHoursDayInfo($day, $year, $month, $idDepartment, $idCoworker, $idReplacement = 0)
    {
        $result = $this->createQueryBuilder('t')
            ->select('t.total as total')
            ->addSelect('t.totalNotOfficially as totalNotOfficially')
            ->addSelect('t.departmentPeopleCooperationId')
            ->addSelect('t.percentCooperation')
            ->addSelect('t.isSick as isSick')
            ->addSelect('t.isSkip as isSkip')
            ->addSelect('t.isFired as isFired')
            ->addSelect('t.isVacation as isVacation')
            ->addSelect('t.isOwnVacation as isOwnVacation')
            ->leftJoin('t.department', 'd')
            ->leftJoin('t.departmentPeople', 'dp')
            ->andWhere('t.departmentPeopleReplacement = :idReplacement')
            ->setParameter(':idReplacement', $idReplacement)
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


    /**
     * @param int $year
     * @param int $month
     * @param int $idDepartment
     * @param int $idCoworker
     * @param int $idReplacement
     *
     * @return array
     */
    public function getSumTotalOfficially($year, $month, $idDepartment, $idCoworker, $idReplacement = 0)
    {
        $result = $this->createQueryBuilder('t')
            ->select('SUM(t.total)')
            ->leftJoin('t.department', 'd')
            ->leftJoin('t.departmentPeople', 'dp')
            ->andWhere('t.departmentPeopleReplacement = :idReplacement')
            ->setParameter(':idReplacement', $idReplacement)
            ->andWhere('t.month = :month')
            ->setParameter(':month', $month)
            ->andWhere('t.year = :year')
            ->setParameter(':year', $year);
        if (is_array($idDepartment)) {
            $result = $result->andWhere('d.id IN (:id)')
                ->setParameter(':id', $idDepartment);
        } else {
            $result = $result->andWhere('d.id = :id')
                ->setParameter(':id', $idDepartment);
        }
            $result = $result->andWhere('dp.id = :idCoworker')
            ->setParameter(':idCoworker', $idCoworker)
            ->getQuery()
            ->getSingleScalarResult();

        return $result;
    }

    /**
     * @param int $year
     * @param int $month
     * @param int $idDepartment
     * @param int $idCoworker
     * @param int $idReplacement
     *
     * @return array
     */
    public function getSumTotalNotOfficially($year, $month, $idDepartment, $idCoworker, $idReplacement = 0)
    {
        $result = $this->createQueryBuilder('t')
            ->select('SUM(t.totalNotOfficially)')
            ->leftJoin('t.department', 'd')
            ->leftJoin('t.departmentPeople', 'dp')
            ->andWhere('t.departmentPeopleReplacement = :idReplacement')
            ->setParameter(':idReplacement', $idReplacement)
            ->andWhere('t.month = :month')
            ->setParameter(':month', $month)
            ->andWhere('t.year = :year')
            ->setParameter(':year', $year);
        if (is_array($idDepartment)) {
            $result = $result->andWhere('d.id IN (:id)')
                ->setParameter(':id', $idDepartment);
        } else {
            $result = $result->andWhere('d.id = :id')
                ->setParameter(':id', $idDepartment);
        }
            $result = $result->andWhere('dp.id = :idCoworker')
            ->setParameter(':idCoworker', $idCoworker)
            ->getQuery()
            ->getSingleScalarResult();

        return $result;
    }
}
