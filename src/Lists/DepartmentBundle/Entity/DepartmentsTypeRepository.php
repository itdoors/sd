<?php

namespace Lists\DepartmentBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * DepartmentsTypeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DepartmentsTypeRepository extends EntityRepository
{

    /**
     * Searches for departments types by $q
     *
     * @param string $q
     *
     * @return mixed[]
     */
    public function getSearchQueryType($q)
    {
        $sql = $this->createQueryBuilder('c')
            ->where('lower(c.name) LIKE :q')
            ->setParameter(':q', mb_strtolower($q, 'UTF-8') . '%')
            ->getQuery();

        return $sql->getResult();
    }
}
