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
            ->andWhere('organizer.isVisited = true');

        if (isset($filters['type']) && $filters['type']) {
            $type = $filters['type'];
        } else {
            $type = 'department';
        }

        $sql = $sql->andWhere('t.name = (:type)')
            ->setParameter(':type', $type);
        //$sql->groupBy('organizer.id');
        if (count($filters)) {
            if (isset($filters['user']) && $filters['user']) {
                $users = explode(',', $filters['user']);
                $sql = $sql->andWhere('organizer.user IN (:user)')
                    ->setParameter(':user', $users);
            }
            if (isset($filters['mpk']) && $filters['mpk']) {
                $mpk = explode(',', $filters['mpk']);
                $sql = $sql->andWhere('d.id IN (
                    SELECT dep.id FROM \Lists\MpkBundle\Entity\Mpk mpk
                    left join mpk.department dep
                    WHERE mpk IN (:mpk)
                    )')
                    ->setParameter(':mpk', $mpk);
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
            if (isset($filters['companyStructure']) && $filters['companyStructure']) {
                $companyStructureFilter = explode(',', $filters['companyStructure']);

                $sql = $sql
                    ->leftJoin('organizer.user', 'u')
                    ->leftJoin('u.stuff', 's')
                    ->leftJoin('s.companystructure', 'c')
                    ->andWhere(
                        "c.id in (:companyStructure) or c.id in
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
                                        cp in (:companyStructure)
                                )"
                    );
                $sql->setParameter(':companyStructure', $companyStructureFilter);

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
            ->addSelect('MAX(organizer.endDatetime) as maxDate')
            ->where('organizer.isVisited = true');
        if (!isset($filters['daterange']) || !$filters['daterange']) {
                $end = new \DateTime();
                $start = clone($end);
                $start->sub(new \DateInterval('P' . 30 . 'D'));
                $sql = $sql
                    ->andWhere('DATE(organizer.startDatetime) >= :start')
                    ->setParameter(':start', $start)
                    ->andWhere('DATE(organizer.endDatetime) <= :end')
                    ->setParameter(':end', $end)
                ;
            }

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

        if (isset($filters['type']) && $filters['type']) {
            $type = $filters['type'];
        } else {
            $type = 'department';
        }

        $sql = $sql->andWhere('t.name = (:type)')
            ->setParameter(':type', $type);

        if (count($filters)) {
            if (isset($filters['user']) && $filters['user']) {
                $users = explode(',', $filters['user']);
                $sql = $sql->andWhere('organizer.user IN (:user)')
                    ->setParameter(':user', $users);
            }
            if (isset($filters['mpk']) && $filters['mpk']) {
                $mpk = explode(',', $filters['mpk']);
                $sql = $sql->andWhere('d.id IN (
                    SELECT dep.id FROM \Lists\MpkBundle\Entity\Mpk mpk
                    left join mpk.department dep
                    WHERE mpk IN (:mpk)
                    )')
                    ->setParameter(':mpk', $mpk);
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
            if (isset($filters['companyStructure']) && $filters['companyStructure']) {
                $companyStructureFilter = explode(',', $filters['companyStructure']);

                $sql = $sql
                    ->leftJoin('organizer.user', 'u')
                    ->leftJoin('u.stuff', 's')
                    ->leftJoin('s.companystructure', 'c')
                    ->andWhere(
                    "c.id in (:companyStructure) or c.id in
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
                                        cp in (:companyStructure)
                                )"
                );
                $sql->setParameter(':companyStructure', $companyStructureFilter);

            }

            if (isset($filters['daterange']) && $filters['daterange']) {
                if ($filters['daterange']['start'] || $filters['daterange']['end']) {
                    $start = $filters['daterange']['start'];
                    $end = $filters['daterange']['end'];

                    $sql = $sql
                        ->andWhere('DATE(organizer.startDatetime) >= :start')
                        ->setParameter(':start', $start)
                        ->andWhere('DATE(organizer.endDatetime) <= :end')
                        ->setParameter(':end', $end)

                    ;
                }
            }

        }
        return $sql;
    }



    public function getCoworkerStatistic($operManager, $filters = null)
    {
        $sql = $this->createQueryBuilder('organizer')
            ->select('COUNT( organizer.id)')
            ->where('organizer.isVisited = true');

        $filters['user'] = $operManager['id'];
        $sql = $this->addFilters($sql, $filters);

        return $sql->getQuery()->getSingleScalarResult();
    }
}
