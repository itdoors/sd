<?php

namespace SD\TaskBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TaskUserRoleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TaskUserRoleRepository extends EntityRepository
{

    private $notViewingStages = array('closed', 'undone', 'done');
    private $viewingRoleMatching = array('author', 'matcher');
    /**
     * @param integer $userId
     * @param string  $role
     *
     * @return integer
     */
    public function countTasksByRoleAndUser($userId, $role)
    {
        $notViewingStages = $this->notViewingStages;
        $viewingRoleMatching = $this->viewingRoleMatching;

        $sql = $this->createQueryBuilder('tur')
            ->select('COUNT(tur.id)')
            ->leftJoin('tur.role', 'r')
            ->leftJoin('tur.task', 't')
            ->leftJoin('t.stage', 's')
            ->leftJoin('tur.user', 'u')
            ->where('u.id = :userId')
            ->setParameter(':userId', $userId);


        $sql->andWhere('s.name NOT IN (:stage)')
            ->setParameter(':stage', $notViewingStages);

        $sql->andWhere('(r.name IN (:viewingRoleMatching) OR s.name != :stageMatching)')
            ->setParameter(':stageMatching', 'matching')
            ->setParameter(':viewingRoleMatching', $viewingRoleMatching);


        if ($role) {
            $sql->andWhere('r.name = :role')
                ->setParameter(':role', $role);
        }

        return $sql->getQuery()->getSingleScalarResult();
    }

    /**
     * @param array $filterArray
     * @param mixed $type
     *
     * @return array|mixed
     */
    public function getEntitiesListFilter1($filterArray, $type = null)
    {
        $notViewingStages = $this->notViewingStages;
        $viewingRoleMatching = $this->viewingRoleMatching;
        $roleMatching = array('matcher');

        if ($type == 'count') {
            $sql = $this->getBaseQueryListFilterCount();
        } else {
            $sql = $this->getBaseQueryListFilter();
        }


        $user = $filterArray['user'];

        if (isset($filterArray['role']) && $filterArray['role'] == 'old') {
            $sql->where('s.name IN (:stage)')
                ->setParameter(':stage', $notViewingStages);
        } else {
            $sql->where('s.name NOT IN (:stage)')
                ->setParameter(':stage', $notViewingStages);
        }
        if (!isset($filterArray['role']) || $filterArray['role'] == '') {
            $sql->andWhere('(r.name IN (:viewingRoleMatching) OR s.name != :stageMatching)')
                ->setParameter(':stageMatching', 'matching')
                ->setParameter(':viewingRoleMatching', $viewingRoleMatching);
        } else {
            if ($filterArray['role'] == 'author') {
                $sql->andWhere('s.name = (:matchingStage)')
                    ->setParameter(':matchingStage', 'matching')
                    ->andWhere('r.name = (:authorRole)')
                    ->setParameter(':authorRole', 'author')
                    ->andWhere(
                        't.id IN (
                        SELECT tCommit.id FROM \SD\TaskBundle\Entity\TaskCommit as tc
                            LEFT JOIN tc.taskUserRole as turCommit
                            LEFT JOIN turCommit.task as tCommit
                            WHERE t.id = tCommit.id
                                AND turCommit.id NOT IN (
                                    SELECT turCommit.id FROM \SD\TaskBundle\Entity\TaskCommit as tcSigned
                                    LEFT JOIN tcSigned.taskUserRole as turCommitSigned
                                    LEFT JOIN tcSigned.stage as stageTaskCommit
                                        WHERE stageTaskCommit.name = :sign_up
                                            AND  turCommit.id = turCommitSigned.id
                                )
                        )'
                    )
                    ->setParameter(':sign_up', 'sign_up');

            } elseif ($filterArray['role'] == 'controller') {
                $sql->andWhere('s.name IN (:controllingStage)')
                    ->setParameter(':controllingStage', array('performing', 'created', 'checking', 'date_request'))
                    ->andWhere('r.name = (:controllingRole)')
                    ->setParameter(':controllingRole', 'controller');
            } elseif ($filterArray['role'] == 'matcher') {
                $sql->andWhere('s.name = (:matchingStage)')
                    ->setParameter(':matchingStage', 'matching')
                    ->andWhere('r.name = (:matchingRole)')
                    ->setParameter(':matchingRole', 'matcher')
                    ->andWhere(
                        't.id NOT IN (
                        SELECT tCommit.id FROM \SD\TaskBundle\Entity\TaskCommit as tc
                            LEFT JOIN tc.taskUserRole as turCommit
                            LEFT JOIN turCommit.task as tCommit
                            LEFT JOIN tc.stage as stageTaskCommit
                            WHERE t.id = tCommit.id
                                AND stageTaskCommit.name = :sign_up
                                AND turCommit.user = :user
                        )'
                    )
                    ->setParameter(':sign_up', 'sign_up');
            } elseif ($filterArray['role'] == 'performer') {
                $sql->andWhere('s.name IN (:performingStage)')
                    ->setParameter(':performingStage', array('created', 'performing', 'date_request'))
                    ->andWhere('r.name = (:performingRole)')
                    ->setParameter(':performingRole', 'performer');
            } elseif ($filterArray['role'] == 'viewer') {
                $sql->andWhere('tur.isViewed = false')
                    ->andWhere('r.name = (:viewerRole)')
                    ->setParameter(':viewerRole', 'viewer');
            }
        }

            $sql->andWhere('u = :user')
                ->setParameter(':user', $user);

        if ($type == 'count') {
            //$sql->orderBy('t.createDate', 'DESC');
        } else {
            $sql->orderBy('t.createDate', 'DESC');
        }

        if ($type == 'count') {
            return $sql->getQuery()->getSingleScalarResult();
        } else {

            return $sql->getQuery()->getResult();
        }



    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getBaseQueryListFilter()
    {
        $sql = $this->createQueryBuilder('tur')
            ->leftJoin('tur.role', 'r')
            ->leftJoin('tur.task', 't')
            ->leftJoin('t.stage', 's')
            ->leftJoin('tur.user', 'u');

        return $sql;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getBaseQueryListFilterCount()
    {
        $sql = $this->createQueryBuilder('tur')
            ->select('COUNT(tur.id)')
            ->leftJoin('tur.role', 'r')
            ->leftJoin('tur.task', 't')
            ->leftJoin('t.stage', 's')
            ->leftJoin('tur.user', 'u');

        return $sql;
    }

    /**
     * @param array $filterArray
     *
     * @return array
     */
    public function getEntitiesListByFilter($filterArray)
    {

        $notViewingStages = $this->notViewingStages;
        $viewingRoleMatching = $this->viewingRoleMatching;

        //$notViewingMatched = array('matching');
        $roleMatching = array('matcher');

        $sql = $this->createQueryBuilder('tur')
            ->leftJoin('tur.role', 'r')
            ->leftJoin('tur.task', 't')
            ->leftJoin('t.stage', 's')
            ->leftJoin('tur.user', 'u');

        if (isset($filterArray['showClosed']) && $filterArray['showClosed']) {
            $sql->where('s.name IN (:stage)')
                ->setParameter(':stage', $notViewingStages);
        } else {
            $sql->where('s.name NOT IN (:stage)')
            ->setParameter(':stage', $notViewingStages);
        }

        $sql->andWhere('(r.name IN (:viewingRoleMatching) OR s.name != :stageMatching)')
            ->setParameter(':stageMatching', 'matching')
            ->setParameter(':viewingRoleMatching', $viewingRoleMatching);

/*        $sql->andWhere('(r.name NOT IN (:roleMatching) OR s.name = :stageMatching)')
            ->setParameter(':stageMatching', 'matching')
            ->setParameter(':roleMatching', $roleMatching);*/


        if (isset($filterArray['role']) && count($filterArray['role'])) {
            $sql->andWhere('r IN (:role)')
                ->setParameter(':role', $filterArray['role']);
        } else {
            $sql->andWhere('r.name IN (:role)')
                ->setParameter(':role', array('performer'));
        }

        if (isset($filterArray['user'])) {
            $sql->andWhere('u = :user')
                ->setParameter(':user', $filterArray['user']);
        }

        $sql->orderBy('t.createDate', 'DESC');

        return $sql->getQuery()->getResult();
    }

    /**
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return array
     */
    public function getTasksAvailable($user)
    {

        $notViewingStages = $this->notViewingStages;
        $viewingRoleMatching = $this->viewingRoleMatching;

        //$notViewingMatched = array('matching');
        $roleMatching = array('matcher');

        $sql = $this->createQueryBuilder('tur')
            ->select('t.id')
            ->leftJoin('tur.role', 'r')
            ->leftJoin('tur.task', 't')
            ->leftJoin('t.stage', 's')
            ->leftJoin('tur.user', 'u');

        $sql->andWhere('(r.name IN (:viewingRoleMatching) OR s.name != :stageMatching)')
            ->setParameter(':stageMatching', 'matching')
            ->setParameter(':viewingRoleMatching', $viewingRoleMatching);

            $sql->andWhere('u = :user')
                ->setParameter(':user', $user);

        $sql->orderBy('t.createDate', 'DESC');

        return $sql->getQuery()->getResult();
    }
}
