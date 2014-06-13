<?php

namespace Lists\DepartmentBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * DepartmentsStatusRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DepartmentsStatusRepository extends EntityRepository
{

    /**
     * Searches for departments status by $q
     *
     * @param string $q
     *
     * @return mixed[]
     */
    public function getSearchQueryStatus($q)
    {
        $sql = $this->createQueryBuilder('c')
            ->where('lower(c.name) LIKE :q')
            ->setParameter(':q', mb_strtolower($q, 'UTF-8') . '%')
            ->getQuery();

        return $sql->getResult();
    }
}
