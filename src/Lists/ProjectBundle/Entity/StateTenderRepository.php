<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * StateTenderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StateTenderRepository extends EntityRepository
{
    /**
     * getStateTender
     * 
     * @param integer $id
     *
     * @return Query
     */
    public function getStateTender($id)
    {
        /** @var \Doctrine\ORM\QueryBuilder $sql */
        $sql = $this->createQueryBuilder('st');

        // where
        $sql->where('st.id = :id')
            ->setParameter(':id', $id);

        $query = $sql->getQuery();

        return $query->getSingleResult();
    }
    /**
     * getListStateTender
     * 
     * @param User   $user
     * @param string $status active|closed
     *
     * @return Query
     */
    public function getListStateTender($user, $status)
    {
        /** @var \Doctrine\ORM\QueryBuilder $sql */
        $sql = $this->createQueryBuilder('p');
        /** @var \Doctrine\ORM\QueryBuilder $sqlCount */
        $sqlCount = $this->createQueryBuilder('p');
        
        // select
        $sqlCount->select('COUNT(DISTINCT p.id)');
        
        //where
        if ($status == 'active') {
            $sql->where('p.isClosed = false or p.isClosed is null');
            $sqlCount->where('p.isClosed = false or p.isClosed is null');
        } elseif ($status == 'closed') {
            $sql->where('p.isClosed = true');
            $sqlCount->where('p.isClosed = true');
        }
        if ($user) {
            $sql->leftJoin('p.managers', 'm')
                ->andWhere('m.user = :user')
                ->setParameter(':user', $user);
            $sqlCount->leftJoin('p.managers', 'm')
                ->andWhere('m.user = :user')
                ->setParameter(':user', $user);
        }
        

        $query = $sql->getQuery();
        $count = $sqlCount->getQuery()->getSingleScalarResult();

        $query->setHint('knp_paginator.count', $count);

        return $query;
    }
}
