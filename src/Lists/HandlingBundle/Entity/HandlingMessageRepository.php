<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\DBAL\Types\Type;
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

    /**
     * get Future messages
     *
     */
    public function getFutureMessages($userIds)
    {
        $sql = $this->createQueryBuilder('hm')
            ->leftJoin('hm.user', 'user')
            ->leftJoin('hm.type', 'type')
            ->where('hm.createdate >= :createdate');

        $sql
            ->setParameter(':createdate', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME);

        if ($userIds && sizeof($userIds)) {
            $sql
                ->andWhere('user.id in (:userIds)')
                ->setParameter(':userIds', $userIds);
        }

        return $sql
            ->getQuery()
            ->getResult();
    }

    public function getAdvancedResult($from, $to)
    {
        $q = $this->createQueryBuilder('hm')
            ->where('hm.createdate >= :createdateFrom')
            ->andWhere('hm.createdate <= :createdateTo')
            ->leftJoin('hm.user', 'user')
            ->setParameter(':createdateFrom', $from, \Doctrine\DBAL\Types\Type::DATETIME)
            ->setParameter(':createdateTo', $to, \Doctrine\DBAL\Types\Type::DATETIME)
            ->getQuery()
            ->getResult();

        if (!sizeof($q)) {
            return array();
        }

        $types = $this->getEntityManager()->getRepository('ListsHandlingBundle:HandlingMessageType')
            ->getList();

        $result = array();

        foreach ($q as $handlingMessage) {
            if (!isset ( $result[$handlingMessage->getUserId()] )) {
                $result[$handlingMessage->getUserId()] = array();
                $result[$handlingMessage->getUserId()]['user'] = $handlingMessage->getUser();
            }

            $current = 0;

            if (isset( $result[$handlingMessage->getUserId()][$handlingMessage->getTypeId()] )) {
                $current = $result[$handlingMessage->getUserId()][$handlingMessage->getTypeId()];
            }

            $result[$handlingMessage->getUserId()][$handlingMessage->getTypeId()] = $current + 1;
        }

        return $result;
    }
}
