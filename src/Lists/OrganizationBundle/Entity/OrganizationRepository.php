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
    /**
     * @param int[]   $userIds
     * @param mixed[] $filters
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllForSalesQuery($userIds, $filters)
    {
        if (!is_array($userIds) && $userIds) {
            $userIds = array($userIds);
        }

        /** @var \Doctrine\ORM\QueryBuilder $sql*/
        $sql = $this->createQueryBuilder('o');

        /** @var \Doctrine\ORM\QueryBuilder $sqlCount */
        $sqlCount = $this->createQueryBuilder('o');

        $this->processSelect($sql);
        $this->processCount($sqlCount);

        $this->processBaseQuery($sql);
        $sql->where('o.organizationSignId != 61 or o.organizationSignId is NULL');
        $this->processBaseQuery($sqlCount);
        $sqlCount->where('o.organizationSignId != 61 or o.organizationSignId is NULL');

        if (sizeof($userIds)) {
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
     * @param int[]   $userIds
     * @param mixed[] $filters
     *
     * @return \Doctrine\ORM\Query
     */
    public function getCompetitors($userIds, $filters)
    {
        if (!is_array($userIds) && $userIds) {
            $userIds = array($userIds);
        }

        /** @var \Doctrine\ORM\QueryBuilder $sql*/
        $sql = $this->createQueryBuilder('o');

        /** @var \Doctrine\ORM\QueryBuilder $sqlCount */
        $sqlCount = $this->createQueryBuilder('o');

        $this->processSelect($sql);
        $this->processCount($sqlCount);

        $this->processBaseQuery($sql);
        $sql->where('o.organizationSignId = 61');
        $this->processBaseQuery($sqlCount);
        $sqlCount->where('o.organizationSignId = 61');

        if (sizeof($userIds)) {
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
            ->addSelect('o.edrpou')
            ->addSelect('creator.id as creatorId')
            ->addSelect('c.name as cityName')
            ->addSelect('r.name as regionName')
            ->addSelect('scope.name as scopeName')
            ->addSelect(
                "
                array_to_string(
                   ARRAY(
                      SELECT
                        CONCAT(CONCAT(u.lastName, ' '), u.firstName)
                      FROM
                        SDUserBundle:User u
                      LEFT JOIN u.organizations ou
                      WHERE ou.id = o.id
                   ), ','
                 ) as fullNames"
            );
    }

    /**
     * Processes sql query. adding select
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processCount($sql)
    {
        $sql->select('COUNT(DISTINCT o.id) as orgcount');
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
            ->leftJoin('o.scope', 'scope')
            ->leftJoin('o.users', 'users')
            ->leftJoin('o.creator', 'creator')
            ->andWhere('o.parent_id is null');

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
            ->andWhere('users.id in (:userIds)')
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
                    case 'organization':
                        $sql
                            ->andWhere("o.id in (:organizationId)");

                        $sql->setParameter(':organizationId', explode(',', $value));
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
                        $sql->andWhere('c.id in (:cityIds)');
                        $sql->setParameter(':cityIds', $value);
                        break;
                    case 'users':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('users.id in (:userFilterIds)');
                        $sql->setParameter(':userFilterIds', $value);
                        break;
                    case 'organizationEdrpou':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('o.edrpou in (:edrpou)');
                        $sql->setParameter(':edrpou', explode(',', $value));
                        break;
                    /*case 'users':
                        if (isset($value[0]) && !$value[0]) {
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
     * @param int    $organizationSignId
     *
     * @return mixed[]
     */
    public function getSearchQuery($q, $organizationSignId = null)
    {
        $sql = $this->createQueryBuilder('o')
            ->where('lower(o.name) LIKE :q')
            ->andWhere('o.parent_id is null')
            ->setParameter(':q', '%'. mb_strtolower($q, 'UTF-8') . '%');

        if ($organizationSignId) {
            $sql->andWhere('o.organizationSignId = :organizationSignId')
                ->setParameter(':organizationSignId', $organizationSignId);
        }

        return $sql->getQuery()->getResult();
    }

    /**
     * Searches organization by $q
     *
     * @param string $q
     *
     * @return mixed[]
     */
    public function getSearchContactsQuery($q)
    {
        $sql = $this->createQueryBuilder('o')
            ->select('DISTINCT(o.id) as organizationId')
            ->addSelect('o.name as organizationName')
            ->addSelect('o.shortname as organizationShortName')
            ->addSelect(
                "
                    array_to_string(
                       ARRAY(
                          SELECT
                            CONCAT(CONCAT(u.lastName, ' '), u.firstName)
                          FROM
                            SDUserBundle:User u
                          LEFT JOIN u.organizations ou
                          WHERE ou.id = o.id
                       ), ', '
                     ) as fullNames
                    "
            )
            ->leftJoin('o.users', 'users')
            ->where('lower(o.name) LIKE :q OR lower(o.shortname) LIKE :q')
            ->andWhere('o.parent_id is null')
            ->setParameter(':q', '%' . mb_strtolower($q, 'UTF-8') . '%')
            ->getQuery();

        return $sql->getResult();
    }

    /**
     * @param string $edrpou
     *
     * @return array
     */
    public function findByEdrpou($edrpou)
    {
        return $this->createQueryBuilder('o')
            ->where('o.edrpou = :edrpou')
            ->andWhere('o.parent_id is null')
            ->setParameter(':edrpou', $edrpou)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $edrpou
     *
     * @return array
     */
    public function findEdrpou($edrpou)
    {
        return $this->createQueryBuilder('o')
            ->where('o.edrpou LIKE :edrpou')
            ->andWhere('o.parent_id is null')
            ->setParameter(':edrpou', $edrpou . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns organization ids with in one organization group
     *
     * @param int $organizationId
     *
     * @return array
     */
    public function getIdsInGroup($organizationId)
    {
        $organization = $this->getEntityManager()->getRepository('ListsOrganizationBundle:Organization')
            ->find($organizationId);

        if (!$organization || !$organization->getGroupId()) {
            return array();
        }

        $sql = $this->createQueryBuilder('o')
            ->select('o.id as id')
            ->where('o.group_id = :groupId')
            ->andWhere('o.parent_id is null')
            ->setParameter(':groupId', $organization->getGroupId())
            ->getQuery()
            ->getResult();

        return $sql;
    }

    /**
     * Searches organization by $q
     *
     * @param string $q
     *
     * @return mixed[]
     */
    public function searchSelfOrganization($q)
    {
        $sql = $this->createQueryBuilder('o')
            ->leftJoin('o.lookup', 'l')
            ->where('lower(o.name) LIKE :q')
            ->setParameter(':q', '%'. mb_strtolower($q, 'UTF-8') . '%')
            ->andWhere('l.lukey = :key')
            ->setParameter(':key', 'organization_sign_own')
            ->getQuery();

        return $sql->getResult();
    }

    /**
     * Searches organization by $q
     *
     * @param string $q
     *
     * @return mixed[]
     */
    public function searchOrganizationFirst($q)
    {
        $sql = $this->createQueryBuilder('o')
            ->where('lower(o.name) LIKE :q')
            ->setParameter(':q', mb_strtolower($q, 'UTF-8') . '%')
            ->getQuery();

        return $sql->getResult();
    }
}
