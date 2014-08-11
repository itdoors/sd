<?php

namespace Lists\ContactBundle\Entity;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * ModelContactRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ModelContactRepository extends EntityRepository
{
    const MODEL_ORGANIZATION = 'organization';
    const MODEL_DEPARTMENT = 'departments';
    const MODEL_HANDLING = 'handling';

    /**
     * @param int[]    $userIds
     * @param int      $organizationId
     * @param int|null $id
     *
     * @return \Doctrine\ORM\Query
     */
    public function getMyOrganizationsContacts($userIds, $organizationId, $id = null)
    {
        if (!is_array($userIds) && $userIds) {
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

        if ($id) {
            $this->processIdQuery($sql, $id);
            $this->processIdQuery($sqlCount, $id);
        } else {
            if (sizeof($userIds)) {
                $this->processUserQuery($sql, $userIds);
                $this->processUserQuery($sqlCount, $userIds);
            }

            if ($organizationId && $organizationId != 0) {
                $this->processOrganizationQuery($sql, $organizationId);
                $this->processOrganizationQuery($sqlCount, $organizationId);
            }
        }

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
            ->select('mc')
            ->addSelect('o.id as organizationId')
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
            ->leftJoin('mc.type', 'modelContactType')
            ->leftJoin('mc.level', 'modelContactLevel')
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
     * @param int[]                      $userIds
     */
    public function processUserQuery($sql, $userIds)
    {
        $sql
            ->andWhere('owner.id in (:userIds) OR creator.id in (:userIds)')
            ->setParameter(':userIds', $userIds);
    }

    /**
     * Processes sql query. adding organization query
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     * @param integer                    $organizationId
     */
    public function processOrganizationQuery($sql, $organizationId)
    {
        $sql
            ->andWhere('o.id = :organizationId')
            ->setParameter(':organizationId', $organizationId);

        $orgIds = $this->getIdsInGroup($organizationId);

        if (sizeof($orgIds)) {
            $sql
                ->orWhere('mc.modelId in (:organizationIds) AND mc.isShared = true')
                ->setParameter(':organizationIds', $orgIds);
        }
    }

    /**
     * Processes sql query. adding id query
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     * @param int                        $id
     */
    public function processIdQuery($sql, $id)
    {
        $sql
            ->andWhere('mc.id = :id')
            ->setParameter(':id', $id);
    }

    /**
     * Processes sql query. adding users query
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processOrdering($sql)
    {
        $sql
            //->orderBy('o.name', 'ASC');
            ->orderBy('mc.id', 'DESC');
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
                            ->andWhere("o.id = :organizationId")
                            ->setParameter(':organizationId', $value);
                        break;
                    case 'users':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('users.id in (:userFilterIds)');
                        $sql->setParameter(':userFilterIds', $value);
                        break;
                }
            }
        }
    }

    /**
     * @param int[] $userIds
     * @param int   $handlingId
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getMyHandlingContacts($userIds, $handlingId)
    {
        $organizationId = $this->getEntityManager()
            ->getRepository('ListsHandlingBundle:Handling')
            ->getOrganizationByHandlingId($handlingId);

        $sql = $this->createQueryBuilder('mc')
            ->select('mc')
            ->addSelect("CONCAT(CONCAT(u.lastName, ' '), u.firstName) as creatorFullName")
            ->addSelect("CONCAT(CONCAT(owner.lastName, ' '), owner.firstName) as ownerFullName")
            ->addSelect("owner.id as ownerId")
            ->addSelect('o.id as organizationId')
            ->addSelect('o.name as organizationName')
            ->leftJoin('mc.user', 'u')
            ->leftJoin('mc.owner', 'owner')
            ->leftJoin('ListsOrganizationBundle:Organization', 'o', 'WITH', 'o.id = mc.modelId')
            ->where('mc.modelName = :modelName')
            ->andWhere('mc.modelId = :modelId')
            //->andWhere('mc.user_id in (:userIds)')
            //->setParameter(':userIds', $userIds)
            ->setParameter(':modelId', $organizationId)
            ->setParameter(':modelName', self::MODEL_ORGANIZATION);

        $this->processOrganizationQuery($sql, $organizationId);

        return $sql;
    }

    /**
     * Processes search base query
     *
     * @param Query $sql
     * @param int   $organizationId
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
            ->addSelect("owner.id as ownerId")
            ->leftJoin('mc.user', 'creator')
            ->leftJoin('mc.owner', 'owner')
            ->leftJoin('ListsOrganizationBundle:Organization', 'o', 'WITH', 'o.id = mc.modelId')
            ->where('mc.modelName = :modelName')
            ->setParameter(':modelName', self::MODEL_ORGANIZATION);

        if ($organizationId) {
            $sql
                ->andWhere('mc.modelId = :modelId')
                ->setParameter(':modelId', $organizationId);
        }
    }

    /**
     * Returns searched results
     *
     * @param string $searchText
     * @param int    $organizationId
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
     * @param int    $organizationId
     *
     * @return \Doctrine\ORM\Query
     */
    public function getSearchEmailQuery($searchText, $organizationId)
    {
        $sql = $this->createQueryBuilder('mc');

        $this->processSearchBaseQuery($sql, $organizationId);

        $sql
            ->andWhere('mc.email LIKE :searchText')
            ->setParameter(':searchText', '%' . mb_strtolower($searchText, 'UTF-8') . '%');

        return $sql;
    }

    /**
     * Returns organization ids with in one organization group
     *
     * @param int $organizationId
     *
     * @return mixed
     */
    public function getIdsInGroup($organizationId)
    {
        return $this->getEntityManager()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->getIdsInGroup($organizationId);
    }

    /**
     * @param int $organizationId
     * @param int $id
     *
     * @return \Doctrine\ORM\Query
     */
    public function getMyDepartmentByOrganizationContactsQuery($organizationId, $id = null)
    {
        $sql = $this->createQueryBuilder('mc')
            ->select('mc')
            ->addSelect("d.id as departmentId")
            ->addSelect("d.address as departmentAddress")
            ->addSelect("CONCAT(CONCAT(u.lastName, ' '), u.firstName) as creatorFullName")
            ->addSelect("CONCAT(CONCAT(owner.lastName, ' '), owner.firstName) as ownerFullName")
            ->addSelect("owner.id as ownerId")
            ->leftJoin('mc.user', 'u')
            ->leftJoin('mc.owner', 'owner')
            ->leftJoin('ListsDepartmentBundle:Departments', 'd', 'WITH', 'd.id = mc.modelId')
            ->where('mc.modelName = :modelName')
            ->setParameter(':modelName', self::MODEL_DEPARTMENT);

        if ($organizationId) {
            $sql
                ->andWhere('d.organizationId = :organizationId')
                ->setParameter(':organizationId', $organizationId);
        }

        if ($id) {
            $sql
                ->andWhere('mc.id = :id')
                ->setParameter(':id', $id);
        }

        return $sql
            ->getQuery();
    }

    /**
     * @param int $organizationId
     * @param int $id
     *
     * @return mixed[]
     */
    public function getMyDepartmentByOrganizationContacts($organizationId, $id = null)
    {
        /** @var \Doctrine\ORM\Query $sql */
        $sql = $this->getMyDepartmentByOrganizationContactsQuery($organizationId, $id);

        return $sql
            ->getResult();
    }

    /**
     * GetMyContactsByDepartmentId
     *
     * @param int $departmentId
     *
     * @return Query
     */
    public function getMyContactsByDepartmentId($departmentId)
    {
        $sql = $this->createQueryBuilder('mc')
            ->select('mc')
            ->addSelect("mc.modelId as departmentId")
            ->addSelect("CONCAT(CONCAT(u.lastName, ' '), u.firstName) as creatorFullName")
            ->addSelect("CONCAT(CONCAT(owner.lastName, ' '), owner.firstName) as ownerFullName")
            ->addSelect("owner.id as ownerId")
            ->leftJoin('mc.user', 'u')
            ->leftJoin('mc.owner', 'owner')
            ->where('mc.modelName = :modelName')
            ->andWhere('mc.modelId = :modelId')
            ->setParameter(':modelName', self::MODEL_DEPARTMENT)
            ->setParameter('modelId', $departmentId);

        return $sql
            ->getQuery();
    }
    /**
     * Returns organization ids with in one organization group
     *
     * @param integer $organizationId
     *
     * @return mixed
     */
    public function getUsersForSendEmail($organizationId)
    {
        return  $sql = $this->createQueryBuilder('mc')
                ->select('mc.email')
                ->addSelect('mc.id')
                ->addSelect('mc.lastName')
                ->addSelect('mc.firstName')
                ->addSelect('mc.middleName')
                ->innerJoin('mc.sendEmail', 'mcse')
                ->where('mc.modelName = :name')
                ->andWhere('mc.modelId = :id')
                ->andWhere('mcse.isSend = :status')
                ->setParameter(':status', 1)
                ->setParameter(':name', 'organization')
                ->setParameter(':id', $organizationId)
                ->getQuery()->getResult();
    }
}
