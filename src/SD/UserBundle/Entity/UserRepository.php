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
    public function getOnlyStuff ()
    {
        return $this->createQueryBuilder('u')
                ->select('u', 'stuff')
                ->innerJoin('u.stuff', 'stuff')
                ->leftJoin('stuff.status', 'st')
//                ->where('st.lukey = :status OR st.id IS NULL')
//                ->setParameter(':status', 'worked')
                ->orderBy('u.lastName', 'ASC');
    }
    /**
     * Get Only stuff
     *
     * @return Query
     */
    public function getAllUsersStuff ()
    {
        return $this->createQueryBuilder('u')
                ->select('u', 'stuff')
                ->leftJoin('u.stuff', 'stuff')
                ->orderBy('u.lastName', 'ASC');
    }
    /**
     * Get Only stuff
     *
     * @param integer $id
     * 
     * @return Query
     */
    public function getStuffById ($id)
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
                ->addselect('u.username')
                ->addselect('st.name as statusName')
                ->addselect('st.id as status')
                ->addselect('u.locked')
                ->addselect('s.id as stuffId')
                ->addselect('s.issues')
                ->addselect('s.mobilephone')
                ->addselect('s.phonePersonal')
                ->addselect('s.phoneInside')
                ->addselect('s.birthPlace')
                ->addselect('s.dateHire')
                ->addselect('s.dateFire')
                ->addselect('s.education')
                ->addselect('c.name as companyName')
                ->leftJoin('u.stuff', 's')
                ->leftJoin('s.status', 'st')
                ->leftJoin('s.companystructure', 'c')
                ->where('u.id = :id')
                ->setParameter(':id', $id)
                ->getQuery()->getSingleResult();
    }
    /**
     * Get users by organization
     *
     * @param int $organizationId
     *
     * @return Query
     */
    public function getOrganizationUsersQuery ($organizationId)
    {
        return $this->createQueryBuilder('u')
                ->select('u', 'stuff')
                ->innerJoin('u.stuff', 'stuff')
                ->leftJoin('stuff.status', 'status')
                ->innerJoin('u.organizationUsers', 'organizationUsers')
                ->innerJoin('organizationUsers.organization', 'organizations')
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
    public function getHandlingUsersQuery ($handlingId)
    {
        return $this->createQueryBuilder('u')
                ->select('u', 'stuff', 'handlings')
                ->innerJoin('u.stuff', 'stuff')
                ->innerJoin('u.handlingUsers', 'handlingUsers')
                ->innerJoin('handlingUsers.handling', 'handlings')
                ->where('handlings.id = :handlingId')
                ->setParameter(':handlingId', $handlingId);
    }
    /**
     * Processes sql query. adding select
     *
     * @param QueryBuilder $sql
     */
    public function processSelect (QueryBuilder $sql)
    {
        $sql
            ->select('u.id as id')
            ->addSelect('u.firstName')
            ->addSelect('u.lastName')
            ->addSelect('u.middleName')
            ->addSelect('u.username')
            ->addSelect('u.position')
            ->addSelect('s.mobilephone')
            ->addSelect('u.email')
            ->addSelect('u.locked')
            ->addselect('st.name as statusName')
            ->addselect('st.id as status')
            ->addSelect('c.name as company');
    }
    /**
     * Processes sql query. adding select
     *
     * @param QueryBuilder $sql
     */
    public function processCount (QueryBuilder $sql)
    {
        $sql
            ->select('COUNT(u.id) as usercount');
    }
    /**
     * Processes sql query. adding base query
     *
     * @param QueryBuilder $sql
     */
    public function processBaseQuery (QueryBuilder $sql)
    {
        $sql->innerJoin('u.stuff', 's')
            ->leftJoin('s.status', 'st')
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
    public function processIdQuery (QueryBuilder $sql, $id)
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
    public function processFilters (QueryBuilder $sql, $filters)
    {
        if (sizeof($filters)) {
            foreach ($filters as $key => $value) {
                if (!$value) {
                    continue;
                }
                switch ($key) {
                    case 'company':
                        $valueArr = explode(',', $value);
                        $sql->andWhere(
                            "c.id in (:company) or c.id in 
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
                                        cp in (:company)
                                )"
                        );
                        $sql->setParameter(':company', $valueArr);
                        break;
                    case 'isActive':
                        $sql->andWhere("u.locked = :active");
                        $sql->setParameter(':active', ($value == 'active' ? 0 : 1));
                        break;
                    case 'status':
                        if ($value == 68) {
                            $sql->andWhere("st.id is NULL or st.id = :status");
                        } else {
                            $sql->andWhere("st.id = :status");
                        }
                        $sql->setParameter(':status', $value);
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
    public function processOrdering ($sql)
    {
        $sql->orderBy('u.firstName', 'ASC');
    }
    /**
     * getAllForUserQuery
     * 
     * @param array           $filters
     * @param integer|boolean $id
     * 
     * @return mixed[]
     */
    public function getAllForUserQuery ($filters, $id = false)
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

        $query = array (
            'entity' => $sql->getQuery(),
            'count' => $sqlCount->getQuery()->getSingleScalarResult()
        );

        return $query;
    }

    /**
     * getBirthdaysForCalendar
     * 
     * @param integer $startTimestamp
     * @param integer $endTimestamp
     * 
     * @return mixed[]
     */
    public function getBirthdaysForCalendar($startTimestamp, $endTimestamp)
    {
        $res = $this->createQueryBuilder('u')
            ->select('u')
            ->innerJoin('u.stuff', 'stuff')
            ->leftJoin('stuff.status', 'st')
            ->where('u.birthday is not null')
            ->andWhere('st.lukey = :status OR st.id IS NULL')
            ->setParameter(':status', 'worked');
        if (date('Y', $startTimestamp) == date('Y', $endTimestamp)) {
            $res->andWhere('dayofyear(u.birthday) >= :dayofyearStart')
                ->andWhere('dayofyear(u.birthday) <= :dayofyearStop');
        } else {
            $res->andWhere('(dayofyear(u.birthday) >= :dayofyearStart) or (dayofyear(u.birthday) <= :dayofyearStop)');
        }
        $res
            ->setParameter(':dayofyearStart', date('z', $startTimestamp)+1)
            ->setParameter(':dayofyearStop', date('z', $endTimestamp)+1);

        return $res->getQuery()->getResult();
    }

    /**
     * @return mixed[]
     */
    public function getAllStuff()
    {
        $result = $this->createQueryBuilder('u')
            ->innerJoin('u.stuff', 's')
            ->leftJoin('s.status', 'st')
            ->where('st.lukey = :status')
            ->orWhere('st.id is NULL')
            ->setParameter(':status', 'worked')
            ->orderBy('u.lastName', 'asc');

        return $result->getQuery()->getResult();
    }

    /**
     * @param string $role
     *
     * @return array
     */
    public function findByRole($role) {
        $qb = $this->createQueryBuilder('u');

        $qb->select('u')
            ->leftJoin('u.groups', 'g')
            ->where('g.roles LIKE :roles')
            ->setParameter('roles', '%"' . $role . '"%');

        return $qb->getQuery()->getResult();
    }
}
