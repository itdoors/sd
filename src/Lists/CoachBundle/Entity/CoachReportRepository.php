<?php

namespace Lists\CoachBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Alpha\A;

/**
 * CoachReportRepository
 */
class CoachReportRepository extends EntityRepository
{
    /**
     * getAll
     * 
     * @param array $filters
     * 
     * @return mixed[]
     */
    public function getAll($filters = [])
    {
        $sql = $this->createQueryBuilder('c');
        $sqlCount = $this->createQueryBuilder('c');

        $sqlCount->select('COUNT(c.id) as reportscount');

        $this->processFilters($sql, $filters);
        $this->processFilters($sqlCount, $filters);

        $sql->orderBy('c.created', 'DESC');

        $query = array (
            'entity' => $sql->getQuery(),
            'count' => $sqlCount->getQuery()->getSingleScalarResult()
        );

        return $query;
    }

    /**
     * getOrganizations
     *
     * @param string $searchText
     *
     * @return mixed[]
     */
    public function getOrganizations($searchText)
    {
        $organizations = $this->createQueryBuilder('r')
            ->select('DISTINCT IDENTITY(d.organization)')
            ->join('r.action', 'a')
            ->join('a.department', 'd')
            ->join('d.organization', 'o')
            ->where('lower(o.name) LIKE :q')
            ->setParameter(':q', '%' . $searchText . '%')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return $organizations;
    }

    /**
     * getAuthors
     *
     * @param string $searchText
     *
     * @return mixed[]
     */
    public function getAuthors($searchText)
    {
        $authors = $this->createQueryBuilder('r')
            ->select('DISTINCT IDENTITY(r.author)')
            ->join('r.author', 'a')
            ->where('lower(a.firstName) LIKE :q OR lower(a.lastName) LIKE :q')
            ->setParameter(':q', '%' . $searchText . '%')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return $authors;
    }

    /**
     * getCities
     *
     * @param string $searchText
     *
     * @return mixed[]
     */
    public function getCities($searchText)
    {
        $organizations = $this->createQueryBuilder('r')
            ->select('DISTINCT IDENTITY(d.city)')
            ->join('r.action', 'a')
            ->join('a.department', 'd')
            ->join('d.city', 'cc')
            ->where('lower(cc.name) LIKE :q')
            ->setParameter(':q', '%' . $searchText . '%')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return $organizations;
    }

    /**
     * getDepartmentsByCityId
     *
     * @param string $searchText
     * @param int    $cityId
     *
     * @return mixed[]
     */
    public function getDepartmentsByCityId($searchText, $cityId)
    {
        $departments = $this->createQueryBuilder('r')
            ->select('DISTINCT IDENTITY(a.department)')
            ->join('r.action', 'a')
            ->join('a.department', 'd', 'WITH', 'd.city = :cityId')
            ->setParameter(':cityId', $cityId)
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return $departments;
    }

    /**
    * Processes sql query depending on filters
    *
    * @param QueryBuilder $sql
    * @param mixed[]      $filters
    */
    private function processFilters(QueryBuilder $sql, $filters)
    {
        if (sizeof($filters)) {
            foreach ($filters as $key => $value) {
                if (!$value) {
                    continue;
                }
                switch ($key) {
                    case 'author':
                        $valueArr = explode(',', $value);
                        $sql->andWhere("c.author in (:authors)");
                        $sql->setParameter(':authors', $valueArr);
                        break;
                    case 'type':
                        $valueArr = explode(',', $value);
                        $sql->join('c.action', 'a');
                        $sql->andWhere("a.type in (:types)");
                        $sql->setParameter(':types', $valueArr);
                        break;
                    case 'topic':
                        $valueArr = explode(',', $value);
                        $sql->join('c.action', 'a2');
                        $sql->andWhere("a2.topic in (:topics)");
                        $sql->setParameter(':topics', $valueArr);
                        break;
                    case 'organization':
                        $valueArr = explode(',', $value);
                        $sql->join('c.action', 'a3');
                        $sql->join('a3.department', 'd');
                        $sql->andWhere("d.organization in (:organizations)");
                        $sql->setParameter(':organizations', $valueArr);
                        break;
                    case 'city':
                        $valueArr = explode(',', $value);
                        $sql->join('c.action', 'a4');
                        $sql->join('a4.department', 'd2');
                        $sql->andWhere("d2.city in (:cities)");
                        $sql->setParameter(':cities', $valueArr);
                        break;
                    case 'members':
                        $valueArr = explode(',', $value);
                        $sql->join('c.action', 'a5');
                        $sql->join('a5.individuals', 'i');
                        $sql->andWhere("i in (:individuals)");
                        $sql->setParameter(':individuals', $valueArr);
                        break;
                    case 'department':
                        $valueArr = explode(',', $value);
                        $sql->join('c.action', 'a6');
                        $sql->join('a6.department', 'd3');
                        $sql->andWhere("d3 in (:departments)");
                        $sql->setParameter(':departments', $valueArr);
                        break;
                    case 'startedAt':
                        $point1 = clone $value;
                        $point2 = clone $value;
                        $point2->modify('+1 day');

                        $sql->join('c.action', 'a7');
                        $sql->andWhere("a7.startedAt BETWEEN :point1 AND :point2");
                        $sql->setParameter(':point1', $point1);
                        $sql->setParameter(':point2', $point2);
                        break;
                }
            }
        }
    }
}
