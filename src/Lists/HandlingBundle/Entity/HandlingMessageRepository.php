<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * HandlingMessageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HandlingMessageRepository extends EntityRepository
{
    /**
     * getMessagesByHandlingId
     *
     */
    public function getMessagesByHandlingId($handlingId)
    {
        return $this->createQueryBuilder('hm')
            ->where('hm.handling_id = :handlingId')
            ->setParameter(':handlingId', $handlingId)
            ->getQuery()
            ->getResult();
    }
}
