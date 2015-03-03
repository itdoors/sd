<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Alpha\A;

/**
 * ClaimDepartmentRepository
 */
class ClaimDepartmentRepository extends EntityRepository
{
    /**
     * Lists all 'not done' ClaimDepartment entities.
     *
     * @return array
     */
    public function findNotDone()
    {
        $query = $this->createQueryBuilder('c')
            ->select('c as claim')
            ->addSelect('im')
            ->addSelect('d')
            ->addSelect('o')
            ->addSelect('cust')
            ->addSelect('ct')
            ->addSelect('cs')
            ->addSelect('reg')
            ->addSelect('i.firstName')
            ->addSelect('i.lastName')
            ->leftJoin('c.importance', 'im')
            ->leftJoin('c.targetDepartment', 'd')
            ->leftJoin('d.organization', 'o')
            ->leftJoin('d.city', 'ct')
            ->leftJoin('ct.region', 'reg')
            ->leftJoin('reg.companystructure', 'cs')
            ->join('c.customer', 'cust')
            ->join('cust.individual', 'i')
            ->where('c.closedAt is NULL')
//             ->andWhere('c.status != :rejected')
//             ->setParameter('done', StatusType::DONE)
//             ->setParameter('rejected', StatusType::REJECTED)
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true);

        return $query->getResult();
    }

    /**
     * Finds Claim entity.
     *
     * @param integer $id
     *
     * @return array
     */
    public function findClaim($id)
    {
        $result = $this->createQueryBuilder('c')
            ->addSelect('r')
            ->addSelect('cust')
            ->addSelect('dep')
            ->addSelect('o')
//            ->addSelect('mpk')
            ->addSelect('ct')
            ->addSelect('ci')
            ->addSelect('p')
            ->addSelect('f')
            ->addSelect('i')
            ->addSelect('imp')
            ->leftJoin('c.claimPerformerRules', 'r')
            ->leftJoin('c.customer', 'cust')
            ->leftJoin('c.files', 'f')
            ->leftJoin('c.targetDepartment', 'dep')
            ->leftJoin('dep.organization', 'o')
            ->leftJoin('dep.city', 'ct')
//            ->leftJoin('dep.mpks', 'mpk')
            ->leftJoin('c.importance', 'imp')
            ->leftJoin('r.claimPerformer', 'p')
            ->leftJoin('p.individual', 'i')
            ->leftJoin('cust.individual', 'ci')
            ->where('c.id = :id')
//            ->andWhere('mpk.active = true')
            ->setParameter('id', $id)
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getResult();

        return $result[0];
    }
}
