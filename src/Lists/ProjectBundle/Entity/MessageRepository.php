<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * MessageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MessageRepository extends EntityRepository
{
      /**
     * getMessagesByProjectId
     *
     * @param integer $id
     *
     * @return array
     */
    public function getMessagesByProjectId($id)
    {
        return $this->createQueryBuilder('m')
            ->where('m.project = :projectId')
            ->setParameter(':projectId', $id)
            ->orderBy('m.eventDatetime', 'DESC')
            ->getQuery()
            ->getResult();
    }
}