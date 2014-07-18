<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * HandlingUserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HandlingUserRepository extends EntityRepository
{
    /**
    * Get users by handling
    *
    * @param int $handlingId
    *
    * @return Query
    */
    public function getHandlingUsersQuery($handlingId)
    {
        return $this->createQueryBuilder('hu')
                ->select('u.firstName')
                ->addSelect('u.lastName')
                ->addSelect('u.email')
                ->addSelect('hu.id')
                ->addSelect('hu.part')
                ->addSelect('s.mobilephone')
                ->addSelect('l.name')
                ->addSelect('l.lukey')
                ->addSelect('hu.userId')
                ->innerJoin('hu.handling', 'h')
                ->leftJoin('hu.lookup', 'l')
                ->innerJoin('hu.user', 'u')
                ->innerJoin('u.stuff', 's')
                ->where('h.id = :handlingId')
                ->setParameter(':handlingId', $handlingId);
    }
    /**
    * Get users by handling
    *
    * @param int $handlingId
    *
    * @return Query
    */
    public function getMaxPart($handlingId)
    {

        return $this->createQueryBuilder('hu')
                ->select('hu.part')
                ->innerJoin('hu.user', 'u')
                ->where('hu.handlingId = :handlingId')
                ->andWhere('hu.lookupId = (
                    SELECT
                        l.id
                    FROM
                        ListsLookupBundle:Lookup l
                    WHERE l.lukey = :lukey)'
                )
                ->setParameter(':handlingId', $handlingId)
                ->setParameter(':lukey', 'manager_project')
                ->getQuery()
                ->getSingleScalarResult();
    }
}