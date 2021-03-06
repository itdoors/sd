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
            ->addOrderBy('m.type', 'DESC')
            ->getQuery()
            ->getResult();
    }
    /**
     * @param mixed[] $filtres
     *
     * @return array
     */
    public function getList($filtres)
    {
        $q = $this->createQueryBuilder('m');
        $this->filter($q, $filtres);
        $q->leftJoin('m.type', 't');
        $res = $q->orderBy('t.name')->getQuery()->getResult();

        return $res;
    }
    /**
     * filter
     * 
     * @param \Doctrine\ORM\QueryBuilder $sql
     * @param mixed[]                    $filters
     * 
     * return \Doctrine\ORM\QueryBuilder
     */
    private function filter ($sql, $filters)
    {
        if (sizeof($filters)) {
            foreach ($filters as $key => $value) {
                if (!$value) {
                    continue;
                }
                switch ($key) {
                    case 'fromDate':
                        $sql->andWhere('m.eventDatetime >= :fromDate');
                        $sql->setParameter(':fromDate', $value, \Doctrine\DBAL\Types\Type::DATETIME);
                        break;
                    case 'toDate':
                        $sql->andWhere('m.eventDatetime <= :toDate');
                        $sql->setParameter(':toDate', $value, \Doctrine\DBAL\Types\Type::DATETIME);
                        break;
                    case 'managers':
                        $value = explode(',', $value);
                        $sql->andWhere($sql->expr()->in('m.user', ':managersIds'));
                        $sql->setParameter(':managersIds', $value);
                        break;
                }
            }
        }
    }
    /**
     * @param mixed[] $filters
     * @param integer $userId
     *
     * @return array
     */
    public function getAdvancedCountResult($filters, $userId)
    {
        $sql = $this->createQueryBuilder('m');
        $this->filter($sql, $filters);
        
        $sql
            ->select('count(m.id) as countAction, t.name as typeAction')
            ->leftJoin('m.type', 't')
            ->andWhere('m.user = :userId')
            ->setParameter(':userId', $userId)
            ->groupBy('t.name')
            ->orderBy('t.name');
        
        return $sql->getQuery()->getResult();
    }
}
