<?php

namespace SD\BusinessRoleBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Alpha\A;

/**
 * ClientRepository
 */
class ClientRepository extends EntityRepository
{
    /**
     * Lists all 'not done' Claim entities.
     *
     * @return array
     */
    public function findAllClients()
    {
        $query = $this->createQueryBuilder('c')
            ->addSelect('i')
            ->join('c.individual', 'i')
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true);

        return $query->getResult();
    }

//     /**
//      * Finds Claim entity.
//      * 
//      * @param integer $id
//      *
//      * @return array
//      */
//     public function findClaim($id)
//     {
//         $result = $this->createQueryBuilder('c')
// //             ->select('c as claim')
//             ->addSelect('r')
//             ->addSelect('cust')
//             ->addSelect('p')
//             ->addSelect('i')
// //             ->addSelect('i.firstName')
// //             ->addSelect('i.lastName')
//             ->leftJoin('c.claimPerformerRules', 'r')
//             ->leftJoin('c.customer', 'cust')
//             ->leftJoin('r.claimPerformer', 'p')
//             ->leftJoin('p.individual', 'i')
//             ->where('c.id = :id')
//             ->setParameter('id', $id)
//             ->getQuery()
//             ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
//             ->getResult();
// // var_dump($result[0]);die();
//         return $result[0];
//     }
}
