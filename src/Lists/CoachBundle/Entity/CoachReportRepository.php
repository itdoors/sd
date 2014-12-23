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
        $organizations = $this->createQueryBuilder('c')
            ->select('DISTINCT IDENTITY(d.organization)')
            ->from('Lists\CoachBundle\Entity\CoachReport', 'r')
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
        $authors = $this->createQueryBuilder('c')
            ->select('DISTINCT IDENTITY(r.author)')
            ->from('Lists\CoachBundle\Entity\CoachReport', 'r')
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
        $organizations = $this->createQueryBuilder('c')
            ->select('DISTINCT IDENTITY(d.organization)')
            ->from('Lists\CoachBundle\Entity\CoachReport', 'r')
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
    * Processes sql query depending on filters
    *
    * @param QueryBuilder $sql
    * @param mixed[]      $filters
    */
    private function processFilters(QueryBuilder $sql, $filters)
    {var_dump($filters);//die();
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
                        $sql->join('c.action', 'aa');
                        $sql->andWhere("aa.topic in (:topics)");
                        $sql->setParameter(':topics', $valueArr);
                        break;
                    case 'organization':
                        $valueArr = explode(',', $value);
                        $sql->join('c.action', 'aaa');
                        $sql->join('aaa.department', 'd');
                        $sql->andWhere("d.organization in (:organizations)");
                        $sql->setParameter(':organizations', $valueArr);
                        break;
                    case 'city':
                        $valueArr = explode(',', $value);
                        $sql->join('c.action', 'aaaa');
                        $sql->join('aaaa.department', 'dd');
                        $sql->andWhere("dd.city in (:cities)");
                        $sql->setParameter(':cities', $valueArr);
                        break;
                    case 'members':
                        $valueArr = explode(',', $value);
                        $sql->join('c.action', 'aaaaa');
                        $sql->join('aaaaa.individuals', 'i');
                        $sql->andWhere("i in (:individuals)");
                        $sql->setParameter(':individuals', $valueArr);
                        break;
                }
            }
        }
    }
}
