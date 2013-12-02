<?php

namespace Lists\OrganizationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * OrganizationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrganizationRepository extends EntityRepository
{
    public function getAllForSalesQuery($userIds, $filters)
    {
        if (!is_array($userIds) && $userIds)
        {
            $userIds = array($userIds);
        }

        /** @var \Doctrine\ORM\QueryBuilder $sql*/
        $sql = $this->createQueryBuilder('o');

        /** @var \Doctrine\ORM\QueryBuilder $sqlCount */
        $sqlCount = $this->createQueryBuilder('o');

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
            ->select('DISTINCT(o.id) as organizationId', 'o.name as organizationName')
            ->addSelect('c.name as cityName')
            ->addSelect('r.name as regionName')
            ->addSelect('scope.name as scopeName')
            ->addSelect("
                array_to_string(
                   ARRAY(
                      SELECT
                        CONCAT(CONCAT(u.lastName, ' '), u.firstName)
                      FROM
                        SDUserBundle:User u
                      LEFT JOIN u.organizations ou
                      WHERE ou.id = o.id
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
            ->select('COUNT(o.id) as orgcount');

    }

    /**
     * Processes sql query. adding base query
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processBaseQuery($sql)
    {
        $sql
            ->leftJoin('o.city', 'c')
            ->leftJoin('c.region', 'r')
            ->leftJoin('o.scope', 'scope');

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
            ->leftJoin('o.users', 'users')
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
            ->orderBy('o.name', 'ASC');
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
                    case 'organization':
                        $sql
                            ->andWhere("o.id = :organizationId");

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
                        $sql->andWhere('c.id in (:cityIds)');
                        $sql->setParameter(':cityIds', $value);
                        break;
                    case 'users':
                        if (isset($value[0]) && !$value[0])
                        {
                            break;
                        }
                        $sql->andWhere('users.id in (:userIds)');
                        $sql->setParameter(':userIds', $value);
                        break;
                    /*case 'users':
                        if (isset($value[0]) && !$value[0])
                        {
                            break;
                        }
                        $query->andWhereIn('ou.user_id', $value);
                        break;*/
                }
            }
        }
    }

    /**
     * Searches organization by $q
     *
     * @param string $q
     *
     * @return mixed[]
     */
    public function getSearchQuery($q)
    {
        $sql = $this->createQueryBuilder('o')
            ->where('lower(o.name) LIKE :q')
            ->setParameter(':q', '%'. mb_strtolower($q, 'UTF-8') . '%')
            ->getQuery();

        return $sql->getResult();
    }
}
