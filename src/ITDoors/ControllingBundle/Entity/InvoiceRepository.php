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
     * @param query $res Description
     * 
     * @return mixed[]
     */
    public function selectInvoicePeriod($res)
    {
        $res
            ->select('i.sum')
            ->addSelect('i.id')
            ->addSelect('i.invoiceId')
            ->addSelect('i.date ')
            ->addSelect('i.dogovorActName')
            ->addSelect('i.dogovorActDate')
            ->addSelect('i.delayDate')
            ->addSelect('i.delayDays')
            ->addSelect('i.delayDaysType')
            ->addSelect('i.dogovorActOriginal')
            ->addSelect('i.dateEnd')
            ->addSelect('i.dateFact')
            ->addSelect('o.name as organizationName')
            ->addSelect('r.name as regionName')
            ->addSelect('d.number as dogovorNumber')
            ->addSelect('d.startdatetime as dogovorStartDatetime')
            ->addSelect('performer.name as performerName')
            ->addSelect('h.note as description');

        return $res;
    }

    /**
     * Returns results for interval future invoice
     *
     * @param query $res Description
     * 
     * @return mixed[]
     */
    public function selectInvoicePeriodCount($res)
    {
        $res->select('COUNT(i.id)');

        return $res;
    }

    /**
     * Returns results for interval future invoice
     * 
     * @param query $res Description
     * 
     * @return mixed[]
     */
    public function joinInvoicePeriod($res)
    {
        $res
            ->leftJoin('i.organization', 'o')
            ->leftJoin('i.dogovor', 'd')
            ->leftJoin('d.performer', 'performer')
            ->leftJoin('o.city', 'c')
            ->leftJoin('c.region', 'r')->leftJoin('i.messages', 'h')
            ->andWhere('h.id = (SELECT max(h2.id) FROM ITDoorsControllingBundle:InvoiceMessage AS h2 WHERE h2.invoiceId = i.id)  OR h.id is NULL');

        return $res;
    }

    /**
     * Returns results for interval future invoice
     * 
     * @param query   $res              desc
     * @param integer $periodmin  desc
     * @param integer $periodmax desc
     * 
     * @return mixed[]
     */
    public function whereInvoicePeriod($res, $periodmin, $periodmax)
    {
        $date = date('Y-m-d');
        $res
            ->andWhere(":date -  i.delayDate >= :periodmin");
        if ($periodmax != 0) {
            $res->andWhere(':date -  i.delayDate <= :periodmax')
                ->setParameter(':periodmax', $periodmax);
        }

        $res = $res->setParameter(':periodmin', $periodmin)
            ->setParameter(':date', $date)
            ->andWhere("i.dateFact is NULL")
            ->andWhere("(i.court is NULL OR i.court = '0')");

        return $res;
    }

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

        $res = $this->createQueryBuilder('i');

        /** select */
        $this->selectInvoicePeriod($res);
        /** join */
        $this->joinInvoicePeriod($res);
        /** where */
        $this->whereInvoicePeriod($res, $periodmin, $periodmax);

        return $res->getQuery();
    }

    /**
     * Returns results for interval future invoice
     *
     * @param int $periodmin Description
     * 
     * @param int $periodmax 0 - no restrictions
     *
     * @return int count
     */
    public function getInvoicePeriodCount($periodmin, $periodmax)
    {

        $rescount = $this->createQueryBuilder('i');

        /** select */
        $this->selectInvoicePeriodCount($rescount);
        /** where */
        $this->whereInvoicePeriod($rescount, $periodmin, $periodmax);

        return $rescount->getQuery()->getSingleScalarResult();
    }

    /**
     * Returns results for interval future invoice
     *
     * @return mixed[]
     */
    public function getInvoiceCourt()
    {
        $id = 1;

        $res = $this->createQueryBuilder('i');
        /** select */
        $this->selectInvoicePeriod($res);
        /** join */
        $this->joinInvoicePeriod($res);

        return $res
                ->andWhere("i.court = :id")
                ->andWhere("i.dateFact is NULL")
                ->orderBy('i.delayDate', 'DESC')
                ->setParameter(':id', $id)->getQuery();
    }

    /**
     * Returns results for interval future invoice
     *
     * @return int count
     */
    public function getInvoiceCourtCount()
    {
        $id = 1;

        $rescount = $this->createQueryBuilder('i');
        /** select */
        $this->selectInvoicePeriodCount($rescount);

        return $rescount
                ->andWhere("i.court = :id")
                ->andWhere("i.dateFact is NULL")
                ->setParameter(':id', $id)
                ->getQuery()
                ->getSingleScalarResult();
    }

    /**
     * Returns results for interval future invoice
     *
     * @return mixed[]
     */
    public function getInvoicePay()
    {
        $res = $this->createQueryBuilder('i');
        /** select */
        $this->selectInvoicePeriod($res);
        /** join */
        $this->joinInvoicePeriod($res);
        /** where */
        $res = $res->andWhere("i.dateFact is not NULL")
            ->orderBy('i.dateEnd', 'DESC');

        return $res->getQuery();
    }

    /**
     * Returns results for interval future invoice
     *
     * @return mixed[]
     */
    public function getInvoicePayCount()
    {
        $rescount = $this->createQueryBuilder('i');

        /** select */
        $this->selectInvoicePeriodCount($rescount);
        /** where */
        $rescount = $rescount->andWhere("i.dateFact is not NULL");

        return $rescount->getQuery()->getSingleScalarResult();
    }

}
