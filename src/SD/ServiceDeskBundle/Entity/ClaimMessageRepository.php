<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Alpha\A;

/**
 * ClaimMessageRepository
 */
class ClaimMessageRepository extends EntityRepository
{
    /**
     * Lists all 'not done' Claim entities.
     * 
     * @param Claim $claim
     *
     * @return array
     */
    public function findByClaim($claim)
    {
        $query = $this->createQueryBuilder('m')
            ->addSelect('user')
            ->addSelect('files')
            ->join('m.user', 'user')
            ->leftJoin('m.files', 'files')
            ->where('m.claim = :claim')
            ->orderBy('m.createdAt', 'DESC')
            ->setParameter('claim', $claim->getId())
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true);

        return $query->getResult();
    }
}
