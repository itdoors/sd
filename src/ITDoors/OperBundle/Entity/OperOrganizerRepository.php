<?php

namespace ITDoors\OperBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * OperOrganizerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OperOrganizerRepository extends EntityRepository
{

    /**
     * @param \Datetime $date
     * @param User      $user
     *
     * @return array
     */
    public function getDepartmentsInMonth($date, $user)
    {
        $month = $date->format('m');
        $year = $date->format('Y');
        $sql = $this->createQueryBuilder('do')
            ->select('d.id');

        $sql->leftJoin('do.department', 'd')
            ->leftJoin('do.user', 'u')
            ->leftJoin('do.type', 't')

            ->where('u = (:user)')
            ->setParameter(':user', $user)
            ->andWhere('MONTH(do.startDatetime) = :month')
            ->setParameter(':month', $month)
            ->andWhere('YEAR(do.startDatetime) = :year')
            ->setParameter(':year', $year)
            ->andWhere('t.name = (:type)')
            ->setParameter(':type', 'department');



        return $sql->getQuery()->getResult();
    }
    /**
     * getStatistic
     * 
     * @param \DateTime $date
     * @param mixed[]   $filter
     * 
     * @return integer
     */
    public function getStatistic($date, $filters = null)
    {

        $sql = $this->createQueryBuilder('organizer')
        ->select('COUNT(organizer.id)')
        ->leftJoin('organizer.department', 'd')
        ->leftJoin('d.organization', 'o')
        ->leftJoin('organizer.type', 't');

        $sql->where('DATE(organizer.startDatetime) = :date')
            ->setParameter(':date', $date)
            ->andWhere('organizer.isVisited = true')
            ->andWhere('t.name = (:type)')
            ->setParameter(':type', 'department');

        //$sql->groupBy('organizer.id');
        if (count($filters)) {
            if (isset($filters['user']) && $filters['user']) {
                $users = explode(',', $filters['user']);
                $sql = $sql->andWhere('organizer.user IN (:user)')
                    ->setParameter(':user', $users);
            }
            if (isset($filters['organization']) && $filters['organization']) {
                $organizations = explode(',', $filters['organization']);
                $sql = $sql->andWhere('o.id IN (:organization)')
                    ->setParameter(':organization', $organizations);
            }
            if (isset($filters['department']) && $filters['department']) {
                $departments = explode(',', $filters['department']);
                $sql = $sql->andWhere('d.id IN (:department)')
                    ->setParameter(':department', $departments);
            }

        }

        return $sql->getQuery()->getSingleScalarResult();


    }
    /**
     * getTotalVisits
     * 
     * @return integer
     */
    public function getTotalVisits($filters = null)
    {

        $sql = $this->createQueryBuilder('organizer')
            ->select('COUNT(organizer.id)');

        $sql = $this->addFilters($sql, $filters);

        return $sql->getQuery()->getSingleScalarResult();
    }

    /**
     * getTotalVisitsCommented
     * 
     * @return integer
     */
    public function getTotalVisitsCommented($filters = null)
    {

        $sql = $this->createQueryBuilder('organizer')
            ->select('COUNT(DISTINCT organizer.id)')

            ->where('organizer.isVisited = true');

        $sql = $this->addFilters($sql, $filters);

        return $sql->getQuery()->getSingleScalarResult();
    }
    /**
     * getAveragePerDayVisits
     * 
     * @return array
     */
    public function getAveragePerDayVisits($filters = null)
    {

        $sql = $this->createQueryBuilder('organizer')
            ->select('COUNT(organizer.id) as countAll')
            ->addSelect('MIN(organizer.startDatetime) as minDate')
            ->addSelect('MAX(organizer.endDatetime) as maxDate');

        $sql = $this->addFilters($sql, $filters);

        return $sql->getQuery()->getSingleResult();
    }

    /**
     * @param $sql
     * @param $filters
     *
     * @return mixed
     */
    private function addFilters($sql, $filters)
    {
        $sql = $sql
            ->leftJoin('organizer.department', 'd')
            ->leftJoin('organizer.type', 't')
            ->leftJoin('d.organization', 'o');

        $sql = $sql->andWhere('t.name = (:type)')
            ->setParameter(':type', 'department');

        if (count($filters)) {
            if (isset($filters['user']) && $filters['user']) {
                $users = explode(',', $filters['user']);
                $sql = $sql->andWhere('organizer.user IN (:user)')
                    ->setParameter(':user', $users);
            }
            if (isset($filters['organization']) && $filters['organization']) {
                $organizations = explode(',', $filters['organization']);
                $sql = $sql->andWhere('o.id IN (:organization)')
                    ->setParameter(':organization', $organizations);
            }
            if (isset($filters['department']) && $filters['department']) {
                $departments = explode(',', $filters['department']);
                $sql = $sql->andWhere('d.id IN (:department)')
                    ->setParameter(':department', $departments);
            }
            if (isset($filters['daterange']) && $filters['daterange']) {
                if ($filters['daterange']['start'] || $filters['daterange']['end']) {
                    $start = $filters['daterange']['start'];
                    $end = $filters['daterange']['end'];

                    $sql = $sql
                        ->andWhere('organizer.startDatetime >= :start')
                        ->setParameter(':start', $start)
                        ->andWhere('organizer.endDatetime <= :end')
                        ->setParameter(':end', $end)

                    ;
                }
            }

        }
        return $sql;
    }
}
