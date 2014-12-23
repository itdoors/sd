<?php

namespace ITDoors\PayMasterBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * PayMasterRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PayMasterRepository extends EntityRepository
{
    /**
     * Returns results for interval future invoice
     *
     * @param string  $tab
     * @param mixed[] $orders
     * 
     * @return QueryBuilder
     */
    public function forTab ($tab, $orders = null)
    {
        $sql = $this->createQueryBuilder('p');
        $sqlCount = $this->createQueryBuilder('p');
        $sqlCount->select('COUNT(p.id)');

        switch ($tab) {
            case 'new':
                $sql->andWhere('p.paymentDate is NULL');
                $sqlCount->andWhere('p.paymentDate is NULL');
                $sql->andWhere('p.isAcceptance = true or p.isAcceptance is null');
                $sqlCount->andWhere('p.isAcceptance = true or p.isAcceptance is null');
                break;
            case 'urgent':
                $date = new \DateTime();
                $sql->andWhere('p.expectedDate <= :date')
                    ->setParameter(':date', $date);
                $sqlCount->andWhere('p.expectedDate <= :date')
                    ->setParameter(':date', $date);
                $sql->andWhere('p.paymentDate is NULL');
                $sqlCount->andWhere('p.paymentDate is NULL');
                $sql->andWhere('p.isAcceptance != false');
                $sqlCount->andWhere('p.isAcceptance != false');
                break;
            case 'payment':
                $sql->andWhere('p.paymentDate is NULL');
                $sqlCount->andWhere('p.paymentDate is NULL');
                $sql->andWhere('p.isAcceptance = true');
                $sqlCount->andWhere('p.isAcceptance = true');
                $sql->andWhere('p.toPay = true');
                $sqlCount->andWhere('p.toPay = true');
                break;
            case 'sponsored':
                $sql->andWhere('p.paymentDate is not NULL');
                $sqlCount->andWhere('p.paymentDate is not NULL');
                break;
            case 'rejected':
                $sql->andWhere('p.isAcceptance = false');
                $sqlCount->andWhere('p.isAcceptance = false');
                break;
        }
        $this->orderPayMaster($sql, $orders);

        $count = $sqlCount->getQuery()->getSingleScalarResult();
        $query = $sql->getQuery();
        $query->setHint('knp_paginator.count', $count);

        return $query;
    }
    /**
     * Returns results for interval future invoice
     *
     * @param QueryBuilder $res
     * @param mixed[]      $orders
     * 
     * @return QueryBuilder
     */
    public function orderPayMaster (QueryBuilder $res, $orders = null)
    {
        if ($orders) {
            foreach ($orders as $key => $value) {
                switch ($key) {
                    case 'isAcceptance':
                        $res->orderBy('p.isAcceptance', $value);
                        break;
                    case 'expectedDate':
                        $res->orderBy('p.expectedDate', $value);
                        break;
                }
            }
        } else {
            $res->orderBy('p.expectedDate', 'DESC');
        }

        return $res;
    }
}
