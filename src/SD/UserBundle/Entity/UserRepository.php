<?php

namespace SD\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    /**
     * Get Only stuff
     *
     * @return Query
     */
    public function getOnlyStuff()
    {
        return $this->createQueryBuilder('u')
                ->select('u', 'stuff')
                ->where('u.isFired = FALSE OR u.isFired IS NULL')
                //->setParameter(':isFired', false)
                ->innerJoin('u.stuff', 'stuff')
                ->orderBy('u.lastName', 'ASC');
    }

    /**
     * Get Only stuff
     *
     * @param integer $id
     * 
     * @return Query
     */
    public function getStuffById($id)
    {
        return $this->createQueryBuilder('u')
                ->select('u.id')
                ->addselect('u.photo')
                ->addselect('u.email')
                ->addselect('u.lastName')
                ->addselect('u.firstName')
                ->addselect('u.middleName')
                ->addselect('u.birthday')
                ->addselect('u.position')
                ->addselect('s.issues')
                ->addselect('s.mobilephone')
                ->addselect('s.phonePersonal')
                ->addselect('s.phoneInside')
                ->addselect('s.birthPlace')
                ->addselect('s.dateFire')
                ->addselect('s.dateHire')
                ->addselect('s.education')
                ->addselect('c.name as companyName')
                ->leftJoin('u.stuff', 's')
                ->leftJoin('s.companystructure', 'c')
                ->where('u.id = :id')
                ->setParameter(':id', $id)
                ->getQuery()->getSingleResult();
    }

    /**
     * Get Only stuff
     *
     * @return Query
     */
    public function getOnlyStuffCompany()
    {
        return $this->createQueryBuilder('u')
                ->select('u.id')
                ->addSelect('c.name')
                ->where('u.isFired = FALSE OR u.isFired IS NULL')
                //->setParameter(':isFired', false)
                ->innerJoin('u.stuff', 'stuff')
                ->innerJoin('stuff.companystructure', 'c')
                ->groupBy('c.id','u.id')
                ->orderBy('u.lastName', 'ASC');
    }

    /**
     * Get users by organization
     *
     * @param int $organizationId
     *
     * @return Query
     */
    public function getOrganizationUsersQuery($organizationId)
    {
        return $this->createQueryBuilder('u')
                ->select('u', 'stuff')
                ->innerJoin('u.stuff', 'stuff')
                ->innerJoin('u.organizations', 'organizations')
                ->where('organizations.id = :organizationId')
                ->setParameter(':organizationId', $organizationId);
    }

    /**
     * Get users by handling
     *
     * @param int $handlingId
     *
     * @return Query
     */
    public function getHandlingUsersQuery($handlingId)
    {
        return $this->createQueryBuilder('u')
                ->select('u', 'stuff')
                ->innerJoin('u.stuff', 'stuff')
                ->innerJoin('u.handlings', 'handlings')
                ->where('handlings.id = :handlingId')
                ->setParameter(':handlingId', $handlingId);
    }

    /**
     * Processes sql query. adding select
     *
     * @param QueryBuilder $sql
     */
    public function processSelect(QueryBuilder $sql)
    {
        $sql
            ->select('u.id as id')
            ->addSelect('u.firstName')
            ->addSelect('u.lastName')
            ->addSelect('u.middleName')
            ->addSelect('u.position')
            ->addSelect('s.mobilephone')
            ->addSelect('u.email')
            ->addSelect('u.isBlocked')
            ->addSelect('u.isFired')
            ->addSelect('c.name as company');
    }

    /**
     * Processes sql query. adding select
     *
     * @param QueryBuilder $sql
     */
    public function processCount(QueryBuilder $sql)
    {
        $sql
            ->select('COUNT(u.id) as usercount');
    }

    /**
     * Processes sql query. adding base query
     *
     * @param QueryBuilder $sql
     */
    public function processBaseQuery(QueryBuilder $sql)
    {
        $sql->innerJoin('u.stuff', 's')
            ->leftJoin('s.companystructure', 'c');
        /* ->leftJoin('o.city', 'c')
          ->leftJoin('c.region', 'r'); */
    }

    /**
     * Processes sql query. adding id query
     *
     * @param QueryBuilder $sql
     * @param int          $id
     */
    public function processIdQuery(QueryBuilder $sql, $id)
    {
        $sql
            ->andWhere('u.id = :id')
            ->setParameter(':id', $id);
    }

    /**
     * Processes sql query depending on filters
     *
     * @param QueryBuilder $sql
     * @param mixed[]      $filters
     */
    public function processFilters(QueryBuilder $sql, $filters)
    {

        if (sizeof($filters)) {
            foreach ($filters as $key => $value) {
                if (!$value) {
                    continue;
                }
                switch ($key) {
                    case 'isActive':
                        $sql->andWhere("u.isBlocked = :active");
                        $sql->setParameter(':active', ($value == 'active' ? 0 : 1));
                        break;
                    case 'isFired':
                        if ($value !== 'fired') {
                            $sql->andWhere("u.isFired is NULL or u.isFired = :fired");
                        } else {
                            $sql->andWhere("u.isFired = :fired");
                        }
                        $sql->setParameter(':fired', ($value == 'fired' ? 1 : 0));
                        break;
                    default:
                        $sql->andWhere("u.id IN (:userIds)");
                        $ids = explode(',', $value);
                        $sql->setParameter(':userIds', $ids);
                }
            }
        }
    }

    /**
     * Processes sql query. adding users query
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processOrdering($sql)
    {
        $sql->orderBy('u.firstName', 'ASC');
    }

    /**
     * getAllForUserQuery
     * 
     * @param array           $filters
     * @param integer|boolean $id
     * 
     * @return type
     */
    public function getAllForUserQuery($filters, $id = false)
    {
        /** @var \Doctrine\ORM\QueryBuilder $sql */
        $sql = $this->createQueryBuilder('u');

        /** @var \Doctrine\ORM\QueryBuilder $sqlCount */
        $sqlCount = $this->createQueryBuilder('u');

        $this->processSelect($sql);
        $this->processCount($sqlCount);

        $this->processBaseQuery($sql);
        $this->processBaseQuery($sqlCount);

        if ($id) {
            $this->processIdQuery($sql, $id);
            $this->processIdQuery($sqlCount, $id);
        } else {
            $this->processFilters($sql, $filters);
            $this->processFilters($sqlCount, $filters);
        }

        $this->processOrdering($sql);

        $query = array(
            'entity' => $sql->getQuery(),
            'count' => $sqlCount->getQuery()->getSingleScalarResult()
        );

        return $query;
    }
}
