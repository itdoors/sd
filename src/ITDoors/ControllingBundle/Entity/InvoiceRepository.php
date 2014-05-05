<?php

namespace ITDoors\ControllingBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * InvoiceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvoiceRepository extends EntityRepository
{
    /**
     * Returns results for interval future invoice
     *
     * @param int $periodmin Description
     * 
     * @param int $periodmax 0 - no restrictions
     *
     * @return mixed[]
     */
    public function getInvoicePeriod($periodmin, $periodmax)
    {
        $date = date('Y-m-d');
        $res = $this->createQueryBuilder('i')
             ->select('i.sum')
             ->addSelect('i.invoiceId')
             ->addSelect('i.date ')
             ->addSelect('i.dogovorActName')
             ->addSelect('i.dogovorActDate')
             ->addSelect('i.postponement')
             ->addSelect('i.dogovorActOriginal')
             ->addSelect('i.description')
             ->addSelect('i.dateEnd')
             ->addSelect('i.dateFact')
             ->addSelect('o.name as organizationName')
             ->addSelect('r.name as regionName')
             ->addSelect('d.number as dogovorNumber')
             ->addSelect('d.startdatetime as dogovorStartDatetime')
            ->leftJoin('i.organization', 'o')
            ->leftJoin('i.dogovor', 'd')
            ->leftJoin('o.city', 'c')
            ->leftJoin('c.region', 'r')
            ->where(":date -  i.dateEnd >= :periodmin");
        if ($periodmax != 0) {
            $res->andWhere(':date -  i.dateEnd <= :periodmax')
                ->setParameter(':periodmax', $periodmax);
        }

        return $res->setParameter(':periodmin', $periodmin)
                ->setParameter(':date', $date)
                ->andWhere("(i.court is NULL OR i.court = '0')")
                ->getQuery()
                ->getResult();
    }

    /**
     * Returns results for interval future invoice
     *
     * @return mixed[]
     */
    public function getInvoiceCourt()
    {
        $id = 1;

        return $this->createQueryBuilder('i')
                ->select('i.sum')
             ->addSelect('i.invoiceId')
             ->addSelect('i.date ')
             ->addSelect('i.dogovorActName')
             ->addSelect('i.dogovorActDate')
             ->addSelect('i.postponement')
             ->addSelect('i.dogovorActOriginal')
             ->addSelect('i.description')
             ->addSelect('i.dateEnd')
             ->addSelect('i.dateFact')
             ->addSelect('o.name as organizationName')
             ->addSelect('r.name as regionName')
             ->addSelect('d.number as dogovorNumber')
             ->addSelect('d.startdatetime as dogovorStartDatetime')
            ->leftJoin('i.organization', 'o')
            ->leftJoin('i.dogovor', 'd')
            ->leftJoin('o.city', 'c')
            ->leftJoin('c.region', 'r')
                ->where("i.court = :id")
                ->orderBy('i.dateEnd', 'DESC')
                ->setParameter(':id', $id)
                ->getQuery()
                ->getResult();
    }
}
