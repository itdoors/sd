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
    public function findWithFilter($user, $closed = false)
    {
        $query = $this->createQueryBuilder('c')
            ->select('c as claim')
            ->addSelect('im')
            ->addSelect('cust')
            ->addSelect('i.firstName')
            ->addSelect('i.lastName')
            ->leftJoin('c.importance', 'im')
            ->join('c.customer', 'cust')
            ->join('cust.individual', 'i');
        if ($closed) {
            $query = $query
                ->where('c.closedAt is NULL');
        }
        if ($user) {
            $query = $query
                ->leftJoin('c.claimPerformerRules', 'cpr')
                ->leftJoin('cpr.claimPerformer', 'performer')
                ->leftJoin('performer.individual', 'performer_individual')
                ->leftJoin('performer_individual.user', 'performer_user')
                ->andWhere('performer_user = :user')
                ->setParameter(':user', $user);
        }

//             ->andWhere('c.status != :rejected')
//             ->setParameter('done', StatusType::DONE)
//             ->setParameter('rejected', StatusType::REJECTED)
        $query = $query->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true);

        return $query->getResult();
    }
}
