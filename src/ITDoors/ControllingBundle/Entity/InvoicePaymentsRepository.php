<?php

namespace ITDoors\ControllingBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * InvoicePaymentsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvoicePaymentsRepository extends EntityRepository
{
    
    /**
     *
     * @param integer $customerId
     *
     * @return mixed[]
     */
    public function getForCustomer($customerId)
    {
        $sql = $this->createQueryBuilder('ip')
            ->select('ip.date')
            ->addSelect('ip.summa as summaPay')
            ->addSelect('i.delayDate')
            ->innerJoin('ip.invoice', 'i')
            ->where('i.customerId = :customerId')
            ->andWhere('i.delayDate < ip.date')
            ->setParameter('customerId',$customerId)
            ->orderBy('ip.date')
            ->getQuery();

        return $sql->getResult();
    }
}
