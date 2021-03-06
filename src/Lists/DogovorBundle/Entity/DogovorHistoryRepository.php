<?php

namespace Lists\DogovorBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * DogovorHistoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DogovorHistoryRepository extends EntityRepository
{
    /**
     * Return list of history actions by dogovorId
     *
     * @param int $dogovorId
     *
     * @return Query
     */
    public function getListByDogovorId($dogovorId)
    {
        return $this->createQueryBuilder('dh')
            ->select('dh.id as id')
            ->addSelect('dh.createdatetime as createdatetime')
            ->addSelect('dh.prolongationDateFrom as prolongationDateFrom')
            ->addSelect('dh.prolongationDateTo as prolongationDateTo')
            ->addSelect("CONCAT(CONCAT(user.lastName, ' '), user.firstName) as userFullName")
            ->addSelect('dogovor.number as dogovorNumber')
            ->addSelect("CONCAT(CONCAT(dopDogovor.id, ' '), dopDogovor.number) as dopDogovorString")
            ->leftJoin('dh.user', 'user')
            ->leftJoin('dh.dogovor', 'dogovor')
            ->leftJoin('dh.dopDogovor', 'dopDogovor')
            ->where('dh.dogovorId = :dogovorId')
            ->setParameter(':dogovorId', $dogovorId)
            ->getQuery();
    }
}
