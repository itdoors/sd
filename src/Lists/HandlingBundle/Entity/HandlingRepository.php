<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\ORM\Query;
use ITDoors\CommonBundle\Entity\BaseRepository;

/**
 * HandlingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HandlingRepository extends BaseRepository
{
    /**
     * @param int[]   $userIds
     * @param mixed[] $filters
     *
     * @return Query
     */
    public function getAllForSalesQuery($userIds, $filters)
    {
        if (!is_array($userIds) && $userIds) {
            $userIds = array($userIds);
        }

        /** @var \Doctrine\ORM\QueryBuilder $sql */
        $sql = $this->createQueryBuilder('h');

        /** @var \Doctrine\ORM\QueryBuilder $sqlCount */
        $sqlCount = $this->createQueryBuilder('h');

        $this->processSelect($sql);
        $this->processCount($sqlCount);

        $this->processBaseQuery($sql);
        $this->processBaseQuery($sqlCount);

        if (sizeof($userIds)) {
            $this->processUserQuery($sql, $userIds);
            $this->processUserQuery($sqlCount, $userIds);
        }

        $this->processFilters($sql, $filters);
        $this->processFilters($sqlCount, $filters);

        $this->processOrdering($sql);

        $query = $sql->andWhere("lookup.lukey = 'manager_organization' or lookup.lukey is NULL")->getQuery();

        $count = $sqlCount->andWhere("lookup.lukey = 'manager_organization' or lookup.lukey is NULL")->getQuery()->getSingleScalarResult();

        $query->setHint('knp_paginator.count', $count);

        return $query;
    }
    /**
     * @param int[]   $userIds
     * @param mixed[] $filters
     *
     * @return Query
     */
    public function getAllForExport($userIds, $filters)
    {
        if (!is_array($userIds) && $userIds) {
            $userIds = array($userIds);
        }

        /** @var \Doctrine\ORM\QueryBuilder $sql */
        $sql = $this->createQueryBuilder('h');

        $this->processSelectForUser($sql);

        $this->processBaseQuery($sql);

        if (sizeof($userIds)) {
            $this->processUserQuery($sql, $userIds);
        }

        $this->processFilters($sql, $filters);

        $sql->addOrderBy('users.id', 'DESC');

        $query = $sql->getQuery()->getResult();

        return $query;
    }

    /**
     * Processes sql query. adding select
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processSelect($sql)
    {
        $sql
            ->select('DISTINCT (h.id) as handlingId')
            ->addSelect('o.name as organizationName')
            ->addSelect('h.createdate as handlingCreatedate')
            ->addSelect('h.lastHandlingDate as handlingLastHandlingDate')
            ->addSelect('h.nextHandlingDate as handlingNextHandlingDate')
            ->addSelect('city.name as cityName')
            ->addSelect('scope.name as scopeName')
            ->addSelect('h.serviceOffered as handlingServiceOffered')
            ->addSelect('h.chance as handlingChance')
            ->addSelect('status.name as statusName')
            ->addSelect('status.percentageString as percentageString')
            ->addSelect('status.progress as progress')
            ->addSelect('result.percentageString as resultPercentageString')
            ->addSelect('result.progress as resultProgress')
            ->addSelect('lookup.lukey as lukey')
            ->addSelect(
                "
                array_to_string(
                    ARRAY(
                        SELECT
                            CONCAT(CONCAT(u.lastName, ' '), u.firstName)
                        FROM
                            SDUserBundle:User u
                        LEFT JOIN u.handlingUsers hu
                        WHERE hu.handlingId = h.id
                    ), ','
                ) as fullNames"
            )
            ->addSelect(
                "
                  array_to_string(
                     ARRAY(
                        SELECT
                          hs.name
                        FROM
                          ListsHandlingBundle:HandlingService hs
                        LEFT JOIN hs.handlings handlings
                        WHERE h.id = handlings.id
                     ), ','
                   ) as serviceList"
            );
    }
    /**
     * Processes sql query. adding select
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processSelectForUser($sql)
    {
        $sql
            ->select('DISTINCT (h.id) ')
            ->addSelect('h.id as handlingId')
            ->addSelect('o.name as organizationName')
            ->addSelect('o.id as organizationId')
            ->addSelect('h.createdate as handlingCreatedate')
            ->addSelect('h.lastHandlingDate as handlingLastHandlingDate')
            ->addSelect('h.nextHandlingDate as handlingNextHandlingDate')
            ->addSelect('city.name as cityName')
            ->addSelect('scope.name as scopeName')
            ->addSelect('h.serviceOffered as handlingServiceOffered')
            ->addSelect('h.chance as handlingChance')
            ->addSelect('status.name as statusName')
            ->addSelect('status.percentageString as percentageString')
            ->addSelect('status.progress as progress')
            ->addSelect('result.percentageString as resultPercentageString')
            ->addSelect('result.progress as resultProgress')
            ->addSelect('users.firstName')
            ->addSelect('users.lastName')
            ->addSelect('users.middleName')
            ->addSelect(
                "
                  array_to_string(
                     ARRAY(
                        SELECT
                          hs.name
                        FROM
                          ListsHandlingBundle:HandlingService hs
                        LEFT JOIN hs.handlings handlings
                        WHERE h.id = handlings.id
                     ), ','
                   ) as serviceList"
            );
    }

    /**
     * Processes sql query. adding select
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processCount($sql)
    {
        $sql
            ->select('COUNT(DISTINCT h.id) as handlingcount');

    }

    /**
     * Processes sql query. adding base query
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processBaseQuery($sql)
    {
        $sql
            ->leftJoin('h.organization', 'o')
            ->leftJoin('o.city', 'city')
            ->leftJoin('o.scope', 'scope')
            ->leftJoin('h.status', 'status')
            ->leftJoin('h.result', 'result')
            ->leftJoin('h.handlingUsers', 'handlingUsers')
            ->leftJoin('handlingUsers.user', 'users')
            ->leftJoin('handlingUsers.lookup', 'lookup');

    }

    /**
     * Processes sql query. adding users query
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     * @param int[]                      $userIds
     */
    public function processUserQuery($sql, $userIds)
    {
        $sql
            ->where('users.id in (:userIds)')
            ->setParameter(':userIds', $userIds);
    }

    /**
     * Processes sql query. adding users query
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processOrdering($sql)
    {
        $sql
            //->orderBy('h.createdatetime', 'DESC');
            ->addOrderBy('h.id', 'DESC');
    }

    /**
     * Processes sql query depending on filters
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     * @param mixed[]                    $filters
     */
    public function processFilters(\Doctrine\ORM\QueryBuilder $sql, $filters)
    {
        if (sizeof($filters)) {

            foreach ($filters as $key => $value) {
                if (!$value) {
                    continue;
                }
                switch ($key) {
                    case 'organization_id':
                        $sql
                            ->andWhere("h.organization_id = :organizationId");

                        $sql->setParameter(':organizationId', $value);
                        break;
                    case 'scope':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('scope.id in (:scopeIds)');
                        $sql->setParameter(':scopeIds', $value);
                        break;
                    case 'city':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('city.id in (:cityIds)');
                        $sql->setParameter(':cityIds', $value);
                        break;
                    case 'users':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('users.id in (:userFilterIds)');
                        $sql->setParameter(':userFilterIds', $value);
                        break;
                    case 'usersString':
                        $value = explode(',', $value);
                        $sql->andWhere('users.id in (:userFilterIds)');
                        $sql->setParameter(':userFilterIds', $value);
                        break;

                    case 'progress':
                        $sql->andWhere('result.progress = :resultProgress');
                        $sql->setParameter(':resultProgress', $value);
                        break;

                    case 'progressNOT':
                        $sql->andWhere('result.progress <> :resultProgress OR result.progress IS NULL');
                        $sql->setParameter(':resultProgress', $value);
                        break;

                    case 'chanceNOT':
                        $sql->andWhere('status.progress NOT IN (:chances) AND h.status_id IS NOT NULL');
                        $sql->setParameter(':chances', $value);
                        break;

                    case 'isClosed':
                        $sql->andWhere('h.isClosed <> true or h.isClosed is null');
                        break;
                }
            }
        }
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function getHandlingShow($id)
    {
        return $this->createQueryBuilder('h')
            ->select('h')
            ->addSelect('o.name as organizationName')
            ->addSelect('o.id as organizationId')
            ->addSelect("CONCAT(CONCAT(u.lastName, ' '), u.firstName) as creatorFullName")
            ->addSelect("CONCAT(CONCAT(closer.lastName, ' '), closer.firstName) as closerFullname")
            ->addSelect(
                "
                  array_to_string(
                     ARRAY(
                        SELECT
                          hsi.id
                        FROM
                          ListsHandlingBundle:HandlingService hsi
                        LEFT JOIN hsi.handlings handlingsi
                        WHERE h.id = handlingsi.id
                     ), ','
                   ) as serviceIds"
            )
            ->addSelect(
                "
                  array_to_string(
                     ARRAY(
                        SELECT
                          hs.name
                        FROM
                          ListsHandlingBundle:HandlingService hs
                        LEFT JOIN hs.handlings handlings
                        WHERE h.id = handlings.id
                     ), ','
                   ) as serviceList"
            )
            ->addSelect('h.closedatetime as closedatetime')
            ->addSelect('status.percentageString as percentageString')
            ->addSelect('status.progress as progress')
            ->addSelect('result.slug as resultSlug')
            ->addSelect('result.percentageString as resultPercentageString')
            ->addSelect('result.progress as resultProgress')
            ->addSelect('
                (
                SELECT
                    hu.userId
                FROM
                    ListsHandlingBundle:HandlingUser hu
                WHERE hu.handlingId = h.id
                AND hu.lookupId = (
                    SELECT
                        l.id
                    FROM
                        ListsLookupBundle:Lookup l
                    WHERE l.lukey = :lukey
                    )
                )

                 as managerProject')
            ->leftJoin('h.organization', 'o')
            ->leftJoin('h.type', 'type')
            ->leftJoin('h.status', 'status')
            ->leftJoin('h.result', 'result')
            ->leftJoin('h.user', 'u')
            ->leftJoin('h.closer', 'closer')
            ->where('h.id = :id')
            ->setParameter(':id', $id)
            ->setParameter(':lukey', 'manager_project')
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * @param int $handlingId
     *
     * @return mixed
     */
    public function getOrganizationByHandlingId($handlingId)
    {
        $sql = $this->createQueryBuilder('h')
            ->where('h.id = :handlingId')
            ->setParameter(':handlingId', $handlingId)
            ->getQuery()
            ->getSingleResult();

        return $sql->getOrganizationId();
    }

    /**
     * Is interval filter
     *
     * @param mixed[] $filters
     *
     * @return bool
     */
    protected function isIntervalFilter($filters = array())
    {
        if (isset($filters['withDaterange']) &&
            isset($filters['daterange']['start']) &&
            isset($filters['daterange']['end'])
        ) {
            if ($filters['daterange']['start'] &&
                $filters['daterange']['end'] &&
                $filters['withDaterange']
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns query of last manager messages
     *
     * @param mixed[] $filters
     *
     * @return Query
     */
    public function getReportLastMessages($filters = array())
    {
        if (!isset($filters['userId']) || !$filters['userId']) {
            return array();
        }

        $isInterval = $this->isIntervalFilter($filters);

        $userId = $filters['userId'];

        $params = array(
            ':futureMessageParam' => HandlingMessage::ADDITIONAL_TYPE_FUTURE_MESSAGE,
            ':userId' => $userId
        );

        if (!$isInterval) {
            return $this->getReportLastMessagesResults($params);

        }

        $params[':startdate'] = $filters['daterange']['start'];
        $params[':enddate'] = $filters['daterange']['end'];

        $prevMessages =
            $this->toKeyValue('handlingId', 'itself', $this->getReportIntervalPrevMessagesResults($params));

        $futureMessages =
            $this->toKeyValue('handlingId', 'itself', $this->getReportIntervalFutureMessagesResults($params));

        $results = $this->mergeMessages($prevMessages, $futureMessages);

        return $results;
    }

    /**
     * Returns query for last messages
     *
     * @param mixed[] $params
     *
     * @return mixed[]
     */
    protected function getReportLastMessagesResults($params)
    {
        $query = "
            SELECT
                h.id as handlingId,
                o.name as organizationName,
                ht1.name as handlingMessageTypeName1,
                hm1.createdate as handlingMessageCreatedate1,
                hm1.description as handlingMessageDescription1,
                hm1.user_id as handlingMessageUserId1,
                CONCAT(
                  CONCAT(
                    CONCAT(
                      CONCAT(
                        CONCAT(
                          CONCAT(contact1.lastName, ' ')
                          , contact1.firstName
                        ), ' | '
                      ), contact1.phone1
                    ), ' | '
                  ), contact1.phone2
                )  as handlingMessageContact1,
                ht2.name as handlingMessageTypeName2,
                hm2.createdate as handlingMessageCreatedate2,
                hm2.description as handlingMessageDescription2,
                hm2.user_id as handlingMessageUserId2,
                CONCAT(
                  CONCAT(
                    CONCAT(
                      CONCAT(
                        CONCAT(
                          CONCAT(contact2.lastName, ' ')
                          , contact2.firstName
                        ), ' | '
                      ), contact2.phone1
                    ), ' | '
                  ), contact2.phone2
                )  as handlingMessageContact2
            FROM
                ListsHandlingBundle:Handling h
                LEFT JOIN h.organization o
                LEFT JOIN h.HandlingMessages hm1
                LEFT JOIN hm1.type ht1
                LEFT JOIN hm1.contact contact1
                LEFT JOIN h.HandlingMessages hm2
                LEFT JOIN hm2.type ht2
                LEFT JOIN hm2.contact contact2

            WHERE
                hm1.id = (SELECT
                        MAX(hm_sub1.id)
                    FROM
                        ListsHandlingBundle:HandlingMessage hm_sub1
                    WHERE
                        hm_sub1.handling_id = h.id AND
                        (hm_sub1.additionalType <> :futureMessageParam OR hm_sub1.additionalType IS NULL) AND
                        hm_sub1.user_id = :userId AND
                        hm_sub1.id < (
                            SELECT
                                MAX(hm_sub11.id)
                            FROM
                                ListsHandlingBundle:HandlingMessage hm_sub11
                            WHERE
                                hm_sub11.handling_id = h.id AND
                                hm_sub11.additionalType = :futureMessageParam AND
                                hm_sub11.user_id = :userId
                        )
                    ) AND
                hm2.id = (
                    SELECT
                        MAX(hm_sub2.id)
                    FROM
                        ListsHandlingBundle:HandlingMessage hm_sub2
                    WHERE
                        hm_sub2.handling_id = h.id AND
                        hm_sub2.additionalType = :futureMessageParam AND
                        hm_sub2.user_id = :userId
                    )
            ORDER BY hm2.createdate DESC";

        return $this->getEntityManager()
            ->createQuery($query)
            ->setParameters($params)
            ->getResult();

    }

    /**
     * Returns result for interval prev messages
     *
     * @param mixed[] $params
     *
     * @return mixed[]
     */
    protected function getReportIntervalPrevMessagesResults($params)
    {
        $query = "
            SELECT
                h.id as handlingId,
                o.name as organizationName,
                ht1.name as handlingMessageTypeName1,
                hm1.createdate as handlingMessageCreatedate1,
                hm1.description as handlingMessageDescription1,
                hm1.user_id as handlingMessageUserId1,
                CONCAT(
                  CONCAT(
                    CONCAT(
                      CONCAT(
                        CONCAT(
                          CONCAT(contact1.lastName, ' ')
                          , contact1.firstName
                        ), ' | '
                      ), contact1.phone1
                    ), ' | '
                  ), contact1.phone2
                ) as handlingMessageContact1,
                ht2.name as handlingMessageTypeName2,
                hm2.createdate as handlingMessageCreatedate2,
                hm2.description as handlingMessageDescription2,
                hm2.user_id as handlingMessageUserId2,
                CONCAT(
                  CONCAT(
                    CONCAT(
                      CONCAT(
                        CONCAT(
                          CONCAT(contact2.lastName, ' ')
                          , contact2.firstName
                        ), ' | '
                      ), contact2.phone1
                    ), ' | '
                  ), contact2.phone2
                )  as handlingMessageContact2
            FROM
                ListsHandlingBundle:Handling h
                LEFT JOIN h.organization o
                LEFT JOIN h.HandlingMessages hm1
                LEFT JOIN hm1.type ht1
                LEFT JOIN hm1.contact contact1
                LEFT JOIN h.HandlingMessages hm2
                LEFT JOIN hm2.type ht2
                LEFT JOIN hm2.contact contact2
            WHERE
                hm1.id = (SELECT
                        MAX(hm_sub1.id)
                    FROM
                        ListsHandlingBundle:HandlingMessage hm_sub1
                    WHERE
                        hm_sub1.handling_id = h.id AND
                        (hm_sub1.additionalType <> :futureMessageParam OR hm_sub1.additionalType IS NULL) AND
                        hm_sub1.user_id = :userId AND
                        hm_sub1.createdate >= :startdate AND
                        hm_sub1.createdate <= :enddate
                    ) AND
                hm2.id = (
                    SELECT
                        MIN(hm_sub2.id)
                    FROM
                        ListsHandlingBundle:HandlingMessage hm_sub2
                    WHERE
                        hm_sub2.handling_id = h.id AND
                        hm_sub2.additionalType = :futureMessageParam AND
                        hm_sub2.user_id = :userId AND
                        hm_sub2.id > (
                            SELECT
                                MAX(hm_sub21.id)
                            FROM
                                ListsHandlingBundle:HandlingMessage hm_sub21
                            WHERE
                                hm_sub21.handling_id = h.id AND
                                (hm_sub21.additionalType <> :futureMessageParam OR hm_sub21.additionalType IS NULL) AND
                                hm_sub21.user_id = :userId AND
                                hm_sub21.createdate >= :startdate AND
                                hm_sub21.createdate <= :enddate
                        )
                    )
            ORDER BY hm2.createdate DESC";

        return $this->getEntityManager()
            ->createQuery($query)
            ->setParameters($params)
            ->getResult();

    }

    /**
     * Returns results for interval future messages
     *
     * @param mixed[] $params
     *
     * @return mixed[]
     */
    protected function getReportIntervalFutureMessagesResults($params)
    {
        $query = "
            SELECT
                h.id as handlingId,
                o.name as organizationName,
                ht3.name as handlingMessageTypeName1,
                hm3.createdate as handlingMessageCreatedate1,
                hm3.description as handlingMessageDescription1,
                hm3.user_id as handlingMessageUserId1,
                CONCAT(
                  CONCAT(
                    CONCAT(
                      CONCAT(
                        CONCAT(
                          CONCAT(contact3.lastName, ' ')
                          , contact3.firstName
                        ), ' | '
                      ), contact3.phone1
                    ), ' | '
                  ), contact3.phone2
                )  as handlingMessageContact1,
                ht4.name as handlingMessageTypeName2,
                hm4.createdate as handlingMessageCreatedate2,
                hm4.description as handlingMessageDescription2,
                hm4.user_id as handlingMessageUserId2,
                CONCAT(
                  CONCAT(
                    CONCAT(
                      CONCAT(
                        CONCAT(
                          CONCAT(contact4.lastName, ' '), contact4.firstName)
                          , ' | '
                        )
                        , contact4.phone1
                      )
                      , ' | '
                    ), contact4.phone2
                  )  as handlingMessageContact2
            FROM
                ListsHandlingBundle:Handling h
                LEFT JOIN h.organization o
                LEFT JOIN h.HandlingMessages hm3
                LEFT JOIN hm3.type ht3
                LEFT JOIN hm3.contact contact3
                LEFT JOIN h.HandlingMessages hm4
                LEFT JOIN hm4.type ht4
                LEFT JOIN hm4.contact contact4

            WHERE
                hm3.id = (
                    SELECT
                      MAX(hm_sub3.id)
                    FROM
                        ListsHandlingBundle:HandlingMessage hm_sub3
                    WHERE
                        hm_sub3.handling_id = h.id AND
                        (hm_sub3.additionalType <> :futureMessageParam OR hm_sub3.additionalType IS NULL) AND
                        hm_sub3.user_id = :userId AND
                        hm_sub3.id < (
                            SELECT
                                MAX(hm_sub31.id)
                            FROM
                                ListsHandlingBundle:HandlingMessage hm_sub31
                            WHERE
                                hm_sub31.handling_id = h.id AND
                                hm_sub31.additionalType = :futureMessageParam AND
                                hm_sub31.user_id = :userId AND
                                hm_sub31.createdate >= :startdate AND
                                hm_sub31.createdate <= :enddate
                        )
                    ) AND
                hm4.id = (
                    SELECT
                        MAX(hm_sub41.id)
                    FROM
                        ListsHandlingBundle:HandlingMessage hm_sub41
                    WHERE
                        hm_sub41.handling_id = h.id AND
                        hm_sub41.additionalType = :futureMessageParam AND
                        hm_sub41.user_id = :userId AND
                        hm_sub41.createdate >= :startdate AND
                        hm_sub41.createdate <= :enddate
                    )
            ORDER BY hm4.createdate DESC";

        return $this->getEntityManager()
            ->createQuery($query)
            ->setParameters($params)
            ->getResult();
    }

    /**
     * toKeyValue
     *
     * @param string  $key
     * @param string  $value
     * @param mixed[] $arr
     *
     * @return mixed[]
     */
    public function toKeyValue($key, $value, $arr)
    {
        $result = array();

        $isItself = $value == 'itself';

        foreach ($arr as $ar) {
            $v = $isItself ? $ar : $arr[$value];

            $result[$ar[$key]] = $v;
        }

        return $result;
    }

    /**
     * Merges messages arrays
     *
     * @param mixed[] $prevMessages
     * @param mixed[] $futureMessages
     *
     * @return mixed[]
     */
    protected function mergeMessages($prevMessages, $futureMessages)
    {
        foreach ($prevMessages as $key => $prevMessage) {
            if (isset($futureMessages[$key]['handlingMessageCreatedate2'])) {
                if ($prevMessage['handlingMessageCreatedate2'] > $futureMessages[$key]['handlingMessageCreatedate2']) {
                    $futureMessages[$key] = $prevMessage;
                }
            } else {
                $futureMessages[$key] = $prevMessage;
            }
        }

        uasort($futureMessages, array($this, 'createdateSort'));

        return $futureMessages;
    }

    /**
     * @param mixed[] $a
     * @param mixed[] $b
     *
     * @return int
     */
    public function createdateSort($a, $b)
    {
        if ($a['handlingMessageCreatedate2'] == $b['handlingMessageCreatedate2']) {
            return 0;
        }

        return ($a['handlingMessageCreatedate2'] < $b['handlingMessageCreatedate2']) ? 1 : -1;
    }

    /**
     * Searches handling by $q
     *
     * @param string $q
     *
     * @return mixed[]
     */
    public function getSearchQuery($q)
    {
        $sql = $this->createQueryBuilder('h')
            ->where('h.id = :q')
            ->setParameter(':q', $q);

        return $sql->getQuery()->getResult();
    }
    /**
     * @param integer $id Organization.id
     *
     * @return Query
     */
    public function getForOrganization($id)
    {
        /** @var \Doctrine\ORM\QueryBuilder $sql */
        $sql = $this->createQueryBuilder('h');

        $this->processSelect($sql);

        $this->processBaseQuery($sql);

        $this->processOrdering($sql);

        $query = $sql
                ->andWhere("h.organization_id = :organizationId")
                ->setParameter(':organizationId', $id)
                ->getQuery()->getResult();

        return $query;
    }
}
