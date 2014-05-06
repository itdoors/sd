<?php

namespace Lists\DepartmentBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Lists\DogovorBundle\Entity\Dogovor;

/**
 * DepartmentsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DepartmentsRepository extends EntityRepository
{
    /**
     * Returns all departments for current dogovor
     *
     * @param int $dogovorId
     *
     * @return Query
     */
    public function getDepartmentsForDogovor($dogovorId)
    {
        /** @var Dogovor $dogovor */
        $dogovor = $this->getEntityManager()
            ->getRepository('ListsDogovorBundle:Dogovor')
            ->find($dogovorId);

        $organizationIds = array(
            $dogovor->getOrganizationId(),
            $dogovor->getCustomerId(),
            $dogovor->getPerformerId()
        );

        $query = $this->createQueryBuilder('d')
            ->where('d.organizationId in (:organiationIds)')
            ->leftJoin('d.city', 'city')
            ->orderBy('city.name', 'ASC')
            ->setParameter(':organiationIds', $organizationIds);

        return $query;
    }
    /**
     * Searches mpk by $q
     *
     * @param string $q
     *
     * @return mixed[]
     */
    public function getSearchQueryMpk($q) {
        $sql = $this->createQueryBuilder('c')
            ->where('lower(c.mpk) LIKE :q')
            ->setParameter(':q', mb_strtolower($q, 'UTF-8') . '%')
            ->getQuery();

        return $sql->getResult();
    }

    /**
     * creates query to find all departments
     *
     * @return string
     */
    public function getAllDepartmentsQuery() {
        $query = $this->createQueryBuilder('d')
            ->select('d.id as id')
            ->addSelect('d.mpk as mpk')
            ->addSelect('d.address as address')
            ->addSelect('o.name as organizationName')
            ->addSelect('r.name as regionName')
            ->addSelect('c.name as cityName')
            ->addSelect('s.name as statusName')
            ->addSelect('t.name as typeName')
            ->addSelect('d.statusDate')
            ->addSelect('d.description')
            ->addSelect("CONCAT(CONCAT(u.lastName, ' '), u.firstName) as opermanagerName")
            ->leftJoin('d.status', 's')
            ->leftJoin('d.organization', 'o')
            ->leftJoin('d.city', 'c')
            ->leftJoin('c.region', 'r')
            ->leftJoin('d.type', 't')
            ->leftJoin('d.opermanager', 'u')
            ->getQuery();

        return $query;
    }

    /**
     * creates count query to find the number of all departments
     *
     * @return integer
     */
    public function countAllDepartments() {
        $countQuery = $this->createQueryBuilder('p')
            ->select('COUNT(p.id) as id')
            ->leftJoin('p.organization', 'o')
            ->leftJoin('p.city', 'c')
            ->leftJoin('c.region', 'r')
            ->getQuery();

        return $countQuery->getSingleScalarResult();
    }

    /**
     * Searches departments through filters
     *
     * @param array $filters
     *
     * @return mixed[]
     */
    public function getFilteredDepartments($filters) {
        $sql = $this->getAllDepartmentsQuery();

/*        if (sizeof($filters))
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
/*                    case 'scope':
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
                        $sql->andWhere('users.id in (:userFilterIds)');
                        $sql->setParameter(':userFilterIds', $value);
                        break;
                    /*case 'users':
                        if (isset($value[0]) && !$value[0])
                        {
                            break;
                        }
                        $query->andWhereIn('ou.user_id', $value);
                        break;
                }
            }
        }*/
        $departments = $sql->getResult();
        return $departments;
    }
}
