<?php

namespace SD\CalendarBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TaskRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TaskRepository extends EntityRepository
{
    /**
     * @param integer $userId
     * @param string  $type
     * 
     * @return array
     */
    public function getPersonalTask ($userId, $type)
    {
        return $this->createQueryBuilder('t')
            ->select('t.id')
            ->addSelect('t.title')
            ->addSelect('t.startDateTime')
            ->addSelect('t.stopDateTime')
            ->addSelect('t.isDone')

            ->where('t.userId = :userid')
            ->andWhere('t.taskType = :types')

            ->setParameter(':userid', $userId)
            ->setParameter(':types', $type)

            ->getQuery()
            ->getResult();
    }

    /**
     * @param integer $userId
     * @param string  $type
     *
     * @return array
     */
    public function getPersonalTaskDone ($userId, $type)
    {
        return $this->createQueryBuilder('t')
            ->select('t.id')
            ->addSelect('t.title')
            ->addSelect('t.startDateTime')
            ->addSelect('t.stopDateTime')
            ->addSelect('t.isDone')

            ->where('t.userId = :userid')
            ->andWhere('t.taskType = :types')
            ->andWhere('t.isDone = :isDone')

            ->setParameter(':userid', $userId)
            ->setParameter(':types', $type)
            ->setParameter(':isDone', true, \PDO::PARAM_BOOL)
            ->orderBy('t.id', 'desc')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }


    /**
     * @param integer $userId
     * @param string  $type
     *
     * @return array
     */
    public function getFivePersonalTaskOpen ($userId, $type)
    {
        return $this->createQueryBuilder('t')
            ->select('t.id')
            ->addSelect('t.title')
            ->addSelect('t.startDateTime')
            ->addSelect('t.stopDateTime')
            ->addSelect('t.isDone')

            ->where('t.userId = :userid')
            ->andWhere('t.taskType = :types')
            ->andWhere('t.isDone = :isDone')

            ->setParameter(':userid', $userId)
            ->setParameter(':types', $type)
            ->setParameter(':isDone', false, \PDO::PARAM_BOOL)
            ->orderBy('t.id', 'desc')
            ->getQuery()
            ->getResult();
    }
}
