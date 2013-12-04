<?php

namespace Lists\ContactBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ModelContactRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ModelContactRepository extends EntityRepository
{
    const MODEL_ORGANIZATION = 'organization';
    const MODEL_HANDLING = 'handling';

    public function getMyOrganizationsContacts($userIds, $organizationId)
    {
        if (!is_array($userIds) && $userIds)
        {
            $userIds = array($userIds);
        }

        /** @var \Doctrine\ORM\QueryBuilder $sql*/
        $sql = $this->createQueryBuilder('mc');

        /** @var \Doctrine\ORM\QueryBuilder $sqlCount */
        $sqlCount = $this->createQueryBuilder('mc');

        $this->processSelect($sql);
        $this->processCount($sqlCount);

        $this->processBaseQuery($sql);
        $this->processBaseQuery($sqlCount);

        if (sizeof($userIds))
        {
            $this->processUserQuery($sql, $userIds);
            $this->processUserQuery($sqlCount, $userIds);
        }

        /*$this->processFilters($sql, $filters);
        $this->processFilters($sqlCount, $filters);*/

        $this->processOrdering($sql);

        if ($organizationId && $organizationId != 0)
        {
            $sql
                ->andWhere('o.id = :organizationId')
                ->setParameter(':organizationId', $organizationId);
        }

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
            ->select('mc')
            ->addSelect('o.name as organizationName')
            ->addSelect("CONCAT(CONCAT(creator.lastName, ' '), creator.firstName) as creatorFullName")
            ->addSelect("CONCAT(CONCAT(owner.lastName, ' '), owner.firstName) as ownerFullName")
            ->addSelect("owner.id as ownerId");
    }


    /**
     * Processes sql query. adding select
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processCount($sql)
    {
        $sql
            ->select('COUNT(mc.id) as mccount');

    }

    /**
     * Processes sql query. adding base query
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processBaseQuery($sql)
    {
        $sql
            ->leftJoin('mc.user', 'creator')
            ->leftJoin('mc.owner', 'owner')
            ->leftJoin('ListsOrganizationBundle:Organization', 'o', 'WITH', 'o.id = mc.modelId')
            //->leftJoin('o.users', 'users')
            ->where('mc.modelName = :modelName')
            ->andWhere('o.id = mc.modelId')
            ->setParameter(':modelName', self::MODEL_ORGANIZATION);

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
            ->andWhere('owner.id in (:userIds) OR creator.id in (:userIds)')
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
                    case 'organization_id':
                        $sql
                            ->andWhere("o.id = :organizationId")
                            ->setParameter(':organizationId', $value);
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

    public function getMyHandlingContacts($userIds, $handlingId)
    {
        $organizationId = $this->getEntityManager()
            ->getRepository('ListsHandlingBundle:Handling')
            ->getOrganizationByHandlingId($handlingId);

        $sql = $this->createQueryBuilder('mc')
            ->select('mc')
            ->addSelect("CONCAT(CONCAT(u.lastName, ' '), u.firstName) as creatorFullName")
            ->leftJoin('mc.user', 'u')
            ->where('mc.modelName = :modelName')
            ->andWhere('mc.modelId = :modelId')
            //->andWhere('mc.user_id in (:userIds)')
            //->setParameter(':userIds', $userIds)
            ->setParameter(':modelId', $organizationId)
            ->setParameter(':modelName', self::MODEL_ORGANIZATION);

        return $sql;
    }

    /**
     * Processes search base query
     */
    public function processSearchBaseQuery($sql, $organizationId)
    {
        $sql
            ->select("mc.id as id")
            ->addSelect("mc.phone1 as phone1")
            ->addSelect("mc.phone2 as phone2")
            ->addSelect("mc.email as email")
            ->addSelect("o.name as organizationName")
            //->addSelect("CONCAT(CONCAT(mc.phone1, ' '), mc.phone2) as phone")
            ->addSelect("CONCAT(CONCAT(creator.lastName, ' '), creator.firstName) as creatorFullName")
            ->addSelect("CONCAT(CONCAT(owner.lastName, ' '), owner.firstName) as ownerFullName")
            ->leftJoin('mc.user', 'creator')
            ->leftJoin('mc.owner', 'owner')
            ->leftJoin('ListsOrganizationBundle:Organization', 'o', 'WITH', 'o.id = mc.modelId')
            ->where('mc.modelName = :modelName')
            ->setParameter(':modelName', self::MODEL_ORGANIZATION);


        if ($organizationId)
        {
            $sql
                ->andWhere('mc.modelId = :modelId')
                ->setParameter(':modelId', $organizationId);
        }
    }

    /**
     * Returns searched results
     *
     * @param string $searchText
     * @param int $organizationId
     *
     * @return \Doctrine\ORM\Query
     */
    public function getSearchPhoneQuery($searchText, $organizationId)
    {
        $sql = $this->createQueryBuilder('mc');

        $this->processSearchBaseQuery($sql, $organizationId);

        $sql
            ->andWhere('mc.phone1 LIKE :searchText OR mc.phone2 LIKE :searchText')
            ->setParameter(':searchText', '%' . $searchText . '%');

        return $sql;
    }

    /**
     * Returns searched results
     *
     * @param string $searchText
     * @param int $organizationId
     *
     * @return \Doctrine\ORM\Query
     */
    public function getSearchEmailQuery($searchText, $organizationId)
    {
        $sql = $this->createQueryBuilder('mc');

        $this->processSearchBaseQuery($sql, $organizationId);

        $sql
            ->andWhere('mc.email LIKE :searchText')
            ->setParameter(':searchText', '%' . $searchText . '%');

        return $sql;
    }
}
