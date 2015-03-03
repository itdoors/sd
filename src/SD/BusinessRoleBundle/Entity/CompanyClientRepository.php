<?php

namespace SD\BusinessRoleBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Alpha\A;

/**
 * CompanyClientRepository
 */
class CompanyClientRepository extends EntityRepository
{
    /**
     * getClientsForDepartmentQuery
     *
     * @param string  $searchText
     * @param integer $depId
     *
     * @return array
     */
    public function getClientsForDepartmentQuery($searchText, $depId)
    {
        $sql = $this->createQueryBuilder('c')
            ->innerJoin('c.departments', 'd')
//             ->where('lower(d.name) LIKE :q')
//             ->orWhere('lower(d.address) LIKE :q')
            ->andWhere('d.id = :depId')
//             ->orderBy('d.city', 'ASC')
            ->setParameter(':depId', $depId)
//             ->setParameter(':q', '%' . mb_strtolower($searchText, 'UTF-8') . '%')
        ;

        return $sql->getQuery()->getResult();
    }
}
