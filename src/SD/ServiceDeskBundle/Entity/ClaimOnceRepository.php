<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Alpha\A;

/**
 * ClaimOnceRepository
 */
class ClaimOnceRepository extends EntityRepository
{
    /**
     * Lists all 'not done' ClaimOnce entities.
     *
     * @return array
     */
    public function findNotDone()
    {
        $query = $this->createQueryBuilder('c')
            ->select('c as claim')
            ->addSelect('im')
            ->addSelect('cust')
            ->addSelect('i.firstName')
            ->addSelect('i.lastName')
            ->leftJoin('c.importance', 'im')
            ->join('c.customer', 'cust')
            ->join('cust.individual', 'i')
            ->where('c.status != :done')
            ->setParameter('done', StatusType::DONE)
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true);

        return $query->getResult();
    }
}
