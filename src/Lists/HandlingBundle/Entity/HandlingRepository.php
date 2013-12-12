<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * HandlingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HandlingRepository extends EntityRepository
{
    public function getAllForSalesQuery($userIds, $filters)
    {
        if (!is_array($userIds) && $userIds)
        {
            $userIds = array($userIds);
        }

        /** @var \Doctrine\ORM\QueryBuilder $sql*/
        $sql = $this->createQueryBuilder('h');

        /** @var \Doctrine\ORM\QueryBuilder $sqlCount */
        $sqlCount = $this->createQueryBuilder('h');

        $this->processSelect($sql);
        $this->processCount($sqlCount);

        $this->processBaseQuery($sql);
        $this->processBaseQuery($sqlCount);

        if (sizeof($userIds))
        {
            $this->processUserQuery($sql, $userIds);
            $this->processUserQuery($sqlCount, $userIds);
        }

        $this->processFilters($sql, $filters);
        $this->processFilters($sqlCount, $filters);

        $this->processOrdering($sql);

        $query = $sql->getQuery();

        $count = $sqlCount->getQuery()->getSingleScalarResult();

        $query->setHint('knp_paginator.count', $count);

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
            ->select('h.id as handlingId')
            ->addSelect('o.name as organizationName')
            ->addSelect('h.createdate as handlingCreatedate')
            ->addSelect('h.lastHandlingDate as handlingLastHandlingDate')
            ->addSelect('city.name as cityName')
            ->addSelect('scope.name as scopeName')
            ->addSelect('h.serviceOffered as handlingServiceOffered')
            ->addSelect('h.chance as handlingChance')
            ->addSelect('status.name as statusName')
            ->addSelect("
                array_to_string(
                    ARRAY(
                        SELECT
                            CONCAT(CONCAT(u.lastName, ' '), u.firstName)
                        FROM
                            SDUserBundle:User u
                        LEFT JOIN u.handlings hu
                        WHERE hu.id = h.id
                    ), ','
                ) as fullNames
            ");
    }


    /**
     * Processes sql query. adding select
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processCount($sql)
    {
        $sql
            ->select('COUNT(h.id) as handlingcount');

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
            ->leftJoin('h.users', 'users');

    }

    /**
     * Processes sql query. adding users query
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     * @param int[] $userIds
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
            ->orderBy('h.createdatetime', 'DESC');
    }

    /**
     * Processes sql query depending on filters
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     * @param mixed[] $filters
     */
    public function processFilters(\Doctrine\ORM\QueryBuilder $sql, $filters)
    {
        if (sizeof($filters))
        {

            foreach($filters as $key => $value)
            {
                if (!$value)
                {
                    continue;
                }
                switch($key)
                {
                    case 'organization_id':
                        $sql
                            ->andWhere("h.organization_id = :organizationId");

                        $sql->setParameter(':organizationId', $value);
                        break;
                    case 'scope':
                        if (isset($value[0]) && !$value[0])
                        {
                            break;
                        }
                        $sql->andWhere('scope.id in (:scopeIds)');
                        $sql->setParameter(':scopeIds', $value);
                        break;
                    case 'city':
                        if (isset($value[0]) && !$value[0])
                        {
                            break;
                        }
                        $sql->andWhere('city.id in (:cityIds)');
                        $sql->setParameter(':cityIds', $value);
                        break;
                    case 'users':
                        if (isset($value[0]) && !$value[0])
                        {
                            break;
                        }
                        $sql->andWhere('users.id in (:userFilterIds)');
                        $sql->setParameter(':userFilterIds', $value);
                        break;
                }
            }
        }
    }

    public function getHandlingShow($id)
    {
       return $this->createQueryBuilder('h')
           ->select('h')
           ->addSelect('o.name as organizationName')
           ->addSelect("CONCAT(CONCAT(u.lastName, ' '), u.firstName) as creatorFullName")
           ->addSelect("
                  array_to_string(
                     ARRAY(
                        SELECT
                          hsi.id
                        FROM
                          ListsHandlingBundle:HandlingService hsi
                        LEFT JOIN hsi.handlings handlingsi
                        WHERE h.id = handlingsi.id
                     ), ','
                   ) as serviceIds
           ")
           ->addSelect("
                  array_to_string(
                     ARRAY(
                        SELECT
                          hs.name
                        FROM
                          ListsHandlingBundle:HandlingService hs
                        LEFT JOIN hs.handlings handlings
                        WHERE h.id = handlings.id
                     ), ','
                   ) as serviceList
           ")
           ->leftJoin('h.organization', 'o')
           ->leftJoin('h.user', 'u')
           ->where('h.id = :id')
           ->setParameter(':id', $id)
           ->getQuery()
           ->getSingleResult();
    }

    public function getOrganizationByHandlingId($handlingId)
    {
        $sql = $this->createQueryBuilder('h')
            ->where('h.id = :handlingId')
            ->setParameter(':handlingId', $handlingId)
            ->getQuery()
            ->getSingleResult();

        return $sql->getOrganizationId();
    }
}
