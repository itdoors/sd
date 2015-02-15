<?php

namespace SD\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * DeputyRepository
 */
class DeputyRepository extends EntityRepository
{
    /**
     * Searches Position by $q
     *
     * @param Stuff $stuff
     *
     * @return mixed[]
     */
    public function findByDeputyStuff($stuff)
    {
        $qb = $this->createQueryBuilder('d');
        $qb->join('d.deputyStuffs', 'ds')
            ->where($qb->expr()->eq('ds.id', $stuff->getId()));

        return $qb->getQuery()->getResult();
    }
}
