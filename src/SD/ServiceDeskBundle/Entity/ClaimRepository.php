<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Alpha\A;

/**
 * ClaimRepository
 */
class ClaimRepository extends EntityRepository
{
    /**
     * Finds Claim entity.
     * 
     * @param integer $id
     *
     * @return array
     */
    public function findClaim($id)
    {
        $result = $this->createQueryBuilder('c')
            ->addSelect('r')
            ->addSelect('cust')
            ->addSelect('ci')
            ->addSelect('p')
            ->addSelect('f')
            ->addSelect('i')
            ->addSelect('imp')
            ->leftJoin('c.claimPerformerRules', 'r')
            ->leftJoin('c.customer', 'cust')
            ->leftJoin('c.files', 'f')
            ->leftJoin('c.importance', 'imp')
            ->leftJoin('r.claimPerformer', 'p')
            ->leftJoin('p.individual', 'i')
            ->leftJoin('cust.individual', 'ci')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getResult();

        return $result[0];
    }

    public function findAllFromUser($user) {
        $result = $this->createQueryBuilder('c')
            ->leftJoin('c.claimPerformerRules', 'cpr')
            ->leftJoin('cpr.claimPerformer', 'cp')
            ->leftJoin('cp.individual', 'i')
            ->leftJoin('i.user', 'u')
            ->where('u = :user')
            ->setParameter(':user', $user);

        $result =  $result->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getResult();

        return $result;
    }
}
