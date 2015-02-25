<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Alpha\A;

/**
 * ClaimDepartmentRepository
 */
class ClaimDepartmentRepository extends EntityRepository
{
    /**
     * Lists all 'not done' ClaimDepartment entities.
     *
     * @return array
     */
    public function findNotDone()
    {
        $query = $this->createQueryBuilder('c')
            ->select('c as claim')
            ->addSelect('im')
            ->addSelect('d')
            ->addSelect('o')
            ->addSelect('cust')
            ->addSelect('ct')
            ->addSelect('cs')
            ->addSelect('reg')
            ->addSelect('i.firstName')
            ->addSelect('i.lastName')
            ->leftJoin('c.importance', 'im')
            ->leftJoin('c.targetDepartment', 'd')
            ->leftJoin('d.organization', 'o')
            ->leftJoin('d.city', 'ct')
            ->leftJoin('ct.region', 'reg')
            ->leftJoin('reg.companystructure', 'cs')
            ->join('c.customer', 'cust')
            ->join('cust.individual', 'i')
            ->where('c.status != :done')
            ->setParameter('done', StatusType::DONE)
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true);

        return $query->getResult();
    }
}
