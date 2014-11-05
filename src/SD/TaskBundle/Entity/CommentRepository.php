<?php

namespace SD\TaskBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends EntityRepository
{

    /**
     * @param int            $idTasks
     * @param null|\Datetime $minDate
     *
     * @return array
     */
    public function getCommentsForTasksId($idTasks, $minDate = null)
    {
        $sql = $this->createQueryBuilder('c')
            ->where('c.model = :model')
            ->setParameter(':model', 'Task')
            ->andWhere('c.modelId IN (:taskIds)')
            ->setParameter(':taskIds', $idTasks);

        if ($minDate) {
            $sql = $sql->andWhere('c.createDatetime > (:date)')
                ->setParameter(':date', $minDate);
        }

        $sql = $sql->orderBy('c.createDatetime', 'DESC');

        return $sql->getQuery()->getResult();
    }
}
