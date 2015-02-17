<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Types\Type;

/**
 * ProjectRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectRepository extends EntityRepository
{
    /**
     * getListProjectSimple
     * 
     * @param User   $user
     *
     * @return Query
     */
    public function getListProjectForTender($user)
    {
        /** @var \Doctrine\ORM\QueryBuilder $sql */
        $sql = $this->createQueryBuilder('p');

        if ($user) {
            $sql
                ->leftJoin('p.managers', 'm', 'WITH', 'm.user = :user')
                ->leftJoin('p.organization', 'o')
                ->leftJoin('o.organizationUsers', 'mo', 'WITH', 'mo.user = :user')
                ->andWhere($sql->expr()->orX('m.user = :user', 'mo.user = :user'))
                ->setParameter(':user', $user);
        }
        $sql->andWhere('p INSTANCE OF :discr')
            ->setParameter(':discr', array(
                'project_simple',
                'project_commercial_tender',
                'project_electronic_trading'
            ));

        return $sql->getQuery();
    }
    /**
     * @param integer $id Organization.id
     *
     * @return Query
     */
    public function getForOrganization($id)
    {
        /** @var \Doctrine\ORM\QueryBuilder $sql */
        $sql = $this->createQueryBuilder('p');

        $query = $sql
            ->innerJoin('p.organization', 'o')
            ->andWhere("o.id = :organizationId")
            ->setParameter(':organizationId', $id)
            ->getQuery()->getResult();

        return $query;
    }
    /**
     * get All messages
     *
     * @param int[]   $userIds
     * @param string  $startTimestamp
     * @param string  $endTimestamp
     * @param mixed[] $filters
     *
     * @return mixed[]
     */
    public function getLastMessages($userIds, $startTimestamp, $endTimestamp, $filters = array())
    {
        /** @var QueryBuilder $sql */
        $sql = $this->createQueryBuilder('p');
        $sql->select('p as project');
        $sql->addSelect('t.name as nameType');
        $sql->addSelect('o.name as nameOrganization');
        $sql->addSelect('mp.eventDatetime');

        $sql
            ->where('mp.eventDatetime >= :startTimestamp')
            ->andWhere('mp.eventDatetime <= :endTimestamp');

        if (isset($filters['userIds']) && $filters['userIds']) {
            $userIds = explode(',', $filters['userIds']);
        }

        $sql
            ->setParameter(':startTimestamp', new \DateTime(date('Y-m-d H:i:s', $startTimestamp)), Type::DATETIME)
            ->setParameter(':endTimestamp', new \DateTime(date('Y-m-d H:i:s', $endTimestamp)), Type::DATETIME);

        $sql->innerJoin('p.messages', 'mp', 'WITH', 'mp.eventDatetime = (SELECT MAX(pmp.eventDatetime) FROM Lists\ProjectBundle\Entity\MessagePlanned as pmp WHERE pmp.project = p )');
        $sql->innerJoin('p.organization', 'o');
        $sql->leftJoin('mp.type', 't');
        if ($userIds && sizeof($userIds)) {
            $sql
                ->innerJoin('mp.user', 'u')
                ->andWhere('u.id in (:userIds)')
                ->setParameter(':userIds', $userIds);
        }

        return $sql
            ->getQuery()
            ->getResult();
    }
}
