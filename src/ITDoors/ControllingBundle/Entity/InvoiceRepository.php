<?php

namespace ITDoors\ControllingBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

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
     * @param QueryBuilder $res Description
     * 
     * @return QueryBuilder
     */
    public function selectInvoiceSum(QueryBuilder $res)
    {
        $res->select('Sum(i.sum) as summa');

        return $res;
    }

    /**
     * Returns results for interval future invoice
     *
     * @param QueryBuilder $res Description
     * 
     * @return QueryBuilder
     */
    public function selectInvoicePeriod(QueryBuilder $res)
    {
        $res
            ->select('i.sum')
            ->addSelect('i.court')
            ->addSelect('i.id')
            ->addSelect('i.invoiceId')
            ->addSelect('i.date')
            ->addSelect('i.bank')
            ->addSelect('i.dogovorActName')
            ->addSelect('i.dogovorActDate')
            ->addSelect('i.delayDate')
            ->addSelect('i.delayDays')
            ->addSelect('i.delayDaysType')
            ->addSelect('i.dogovorActOriginal')
            ->addSelect('i.dateEnd')
            ->addSelect('i.dateFact')
            ->addSelect(
                "array_to_string(
                  ARRAY(
                          SELECT
                            cs.name
                          FROM
                            ITDoorsControllingBundle:InvoiceCompanystructure ics
                          LEFT JOIN ics.companystructure  cs
                          WHERE ics.invoiceId = i.id
                      ), ','
                 ) as responsibles"
            )
            ->addSelect('customer.name as customerName')
            ->addSelect('performer.name as performerName')
            ->addSelect('r.name as regionName')
            ->addSelect('d.number as dogovorNumber')
            ->addSelect('d.startdatetime as dogovorStartDatetime')
            ->addSelect('h.note as description')
            ->addSelect('h.createdate as descriptiondate');

        return $res;
    }

    /**
     * Returns results for interval future invoice
     *
     * @param QueryBuilder $res Description
     * 
     * @return QueryBuilder
     */
    public function selectInvoicePeriodCount(QueryBuilder $res)
    {
        $res->select('COUNT(i.id)');

        return $res;
    }

    /**
     * Returns results for interval future invoice
     * 
     * @param QueryBuilder $res Description
     * 
     * @return QueryBuilder
     */
    public function joinInvoicePeriod(QueryBuilder $res)
    {
        $subQuery = '
          SELECT max(h2.id)
            FROM
          ITDoorsControllingBundle:InvoiceMessage AS h2
            WHERE h2.invoiceId = i.id';

        $res
            ->leftJoin('i.dogovor', 'd')
            ->leftJoin('i.customer', 'customer')
            ->leftJoin('i.performer', 'performer')
            ->leftJoin('performer.city', 'c')
            ->leftJoin('c.region', 'r')
            ->leftJoin('i.messages', 'h')
            ->andWhere("h.id = ({$subQuery})  OR h.id is NULL");

        return $res;
    }

    /**
     * Returns results for interval future invoice
     * 
     * @param QueryBuilder  $res
     * @param integer       $periodmin
     * @param integer       $periodmax
     * 
     * @return QueryBuilder
     */
    public function whereInvoicePeriod(QueryBuilder $res, $periodmin, $periodmax)
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
     * @param integer $periodmin Description
     * 
     * @param integer $periodmax 0 - no restrictions
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

        return $res
                ->orderBy('i.performerEdrpou', 'DESC')->getQuery();
    }

    /**
     * Returns results for interval future invoice
     *
     * @param array $invoiceIds
     *
     * @return mixed[]
     */
    public function getInvoiceIds($invoiceIds)
    {
        $res = $this->createQueryBuilder('i');

        /** select */
        $this->selectInvoicePeriod($res);
        $res->addSelect('i.dogovorNumber');
        $res->addSelect('i.dogovorDate');
        /** join */
        $this->joinInvoicePeriod($res);
        /** where */
        $res->andWhere('i.id in (:ids)')
                ->setParameter(':ids', $invoiceIds);

        return $res
                ->orderBy('i.performerEdrpou', 'DESC')->getQuery()->getResult();
    }

    /**
     * Returns results for interval future invoice
     * 
     * @return mixed[]
     */
    public function getForExel()
    {
        $res = $this->createQueryBuilder('i');

        /** select */
        $this->selectInvoicePeriod($res);
        /** join */
        $this->joinInvoicePeriod($res);

        $date = date('Y-m-d');
        $res->andWhere(":date -  i.delayDate >= :periodmin");

        $res->setParameter(':periodmin', 0)->setParameter(':date', $date);

        return $res
                ->orderBy('i.performerEdrpou', 'DESC')
                ->getQuery()
                ->getResult();
    }
    /**
     * Returns results for interval future invoice
     *
     * @param integer $periodmin Description
     * 
     * @param integer $periodmax 0 - no restrictions
     *
     * @return integer count
     */
    public function getInvoicePeriodCount($periodmin, $periodmax)
    {
        $rescount = $this->createQueryBuilder('i');

        /** select */
        $this->selectInvoicePeriodCount($rescount);
        /** where */
        $this->whereInvoicePeriod($rescount, $periodmin, $periodmax);

        return $rescount
                ->getQuery()->getSingleScalarResult();
    }

     /**
     * Returns results for interval future invoice
     *
     * @return mixed[]
     */
    public function getInvoiceListForDashboard()
    {
        $res = $this->createQueryBuilder('i');

        /** select */
        $this->selectInvoicePeriod($res);
        /** join */
        $this->joinInvoicePeriod($res);
        /** where */
        $res->andWhere('i.delayDays is NULL or i.delayDays = 0');

        return $res
                ->orderBy('i.delayDate', 'DESC')->getQuery();
    }
    /**
     * Returns results for interval future invoice
     * 
     * @return integer count
     */
    public function getInvoiceListForDashboardCount()
    {
        $rescount = $this->createQueryBuilder('i');

        /** select */
        $this->selectInvoicePeriodCount($rescount);
        /** where */
        $rescount->andWhere('i.delayDays is NULL or i.delayDays = 0');

        return $rescount
                ->getQuery()->getSingleScalarResult();
    }

    /**
     * Returns results for interval future invoice
     *
     * @param integer $periodmin Description
     * 
     * @param integer $periodmax 0 - no restrictions
     *
     * @return float summa
     */
    public function getInvoicePeriodSum($periodmin, $periodmax)
    {
        $res = $this->createQueryBuilder('i');

        /** select */
        $this->selectInvoiceSum($res);
        /** where */
        $this->whereInvoicePeriod($res, $periodmin, $periodmax);

        return $res->getQuery()->getResult();
    }

    /**
     * Returns results for interval future invoice
     *
     * @param date $date
     *
     * @return float summa
     */
    public function getSumma($date)
    {
        $res = $this->createQueryBuilder('i');

        /** select */
        $this->selectInvoiceSum($res);
        /** where */

        $res->andWhere(":date  = i.dateEnd or i.delayDate = :date")
            ->setParameter(':date', $date)
            ->andWhere("i.dateFact is NULL");

        return $res->getQuery()->getResult();
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
                ->setParameter(':id', $id)->getQuery();
    }

    /**
     * Returns results for interval future invoice
     *
     * @return mixed[]
     */
    public function getInvoiceCourtSum()
    {
        $id = 1;

        $res = $this->createQueryBuilder('i');
        /** select */
        $this->selectInvoiceSum($res);
        /** join */
        $this->joinInvoicePeriod($res);

        return $res
                ->andWhere("i.court = :id")
                ->setParameter(':id', $id)->getQuery()->getResult();
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
        $date = date('Y-m-d', time() - 2592000);
        $res = $this->createQueryBuilder('i');
        /** select */
        $this->selectInvoicePeriod($res);
        /** join */
        $this->joinInvoicePeriod($res);
        /** where */
        $res = $res->andWhere("i.dateFact is not NULL")
            ->andWhere("i.dateFact >= :date")->setParameter(':date', $date)
            ->orderBy('i.dateEnd', 'DESC');

        return $res->getQuery();
    }
    /**
     * Returns results for interval future invoice
     *
     * @param date $date Description
     * 
     * @return mixed[]
     */
    public function getInvoiceWhen($date)
    {
        $res = $this->createQueryBuilder('i');
        /** select */
        $this->selectInvoicePeriod($res);
        /** join */
        $this->joinInvoicePeriod($res);

        return $res->andWhere("i.dateEnd = :date or i.delayDate = :date")
            ->setParameter(':date', $date)
            ->andWhere("i.dateFact is NULL")
            ->orderBy('i.dateEnd', 'DESC')
            ->getQuery();
    }

    /**
     * Returns results for interval future invoice
     *
     * @return mixed[]
     */
    public function getInvoicePaySum()
    {
        $date = date('Y-m-d', time() - 2592000);
        $res = $this->createQueryBuilder('i');
        /** select */
        $this->selectInvoiceSum($res);
        /** join */
        $this->joinInvoicePeriod($res);
        /** where */
        $res = $res->andWhere("i.dateFact is not NULL")->andWhere("i.dateFact >= :date")->setParameter(':date', $date);

        return $res->getQuery()->getResult();
    }

    /**
     * Returns results for interval future invoice
     *
     * @return mixed[]
     */
    public function getInvoicePayCount()
    {
        $date = date('Y-m-d', time() - 2592000);
        $rescount = $this->createQueryBuilder('i');

        /** select */
        $this->selectInvoicePeriodCount($rescount);
        /** where */
        $rescount = $rescount
            ->andWhere("i.dateFact is not NULL")
            ->andWhere("i.dateFact >= :date")
            ->setParameter(':date', $date);

        return $rescount->getQuery()->getSingleScalarResult();
    }
    /**
     * Returns results for interval future invoice
     *
     * @param date $date Description
     * 
     * @return mixed[]
     */
    public function getInvoiceWhenCount($date)
    {
        $rescount = $this->createQueryBuilder('i');

        /** select */
        $this->selectInvoicePeriodCount($rescount);
        /** where */
        $rescount = $rescount
            ->andWhere("i.dateEnd = :date or i.delayDate = :date")
            ->andWhere("i.dateFact is NULL")
            ->setParameter(':date', $date);

        return $rescount->getQuery()->getSingleScalarResult();
    }

    /**
     * Returns results for interval future invoice
     *
     * @param string $period 30,60,120,160,180,court,pay
     * 
     * @return mixed[]
     */
    public function getEntittyCountSum($period)
    {
        $result = array();
        switch ($period) {
            case 30:
                $result['entities'] = $this->getInvoicePeriod(1, 30);
                $result['count'] = $this->getInvoicePeriodCount(1, 30);
                break;
            case 60:
                $result['entities'] = $this->getInvoicePeriod(31, 60);
                $result['count'] = $this->getInvoicePeriodCount(31, 60);
                break;
            case 120:
                $result['entities'] = $this->getInvoicePeriod(61, 120);
                $result['count'] = $this->getInvoicePeriodCount(61, 120);
                break;
            case 180:
                $result['entities'] = $this->getInvoicePeriod(121, 180);
                $result['count'] = $this->getInvoicePeriodCount(121, 180);
                break;
            case 181:
                $result['entities'] = $this->getInvoicePeriod(181, 0);
                $result['count'] = $this->getInvoicePeriodCount(181, 0);
                break;
            case 'court':
                $result['entities'] = $this->getInvoiceCourt();
                $result['count'] = $this->getInvoiceCourtCount();
                break;
            case 'pay':
                $result['entities'] = $this->getInvoicePay();
                $result['count'] = $this->getInvoicePayCount();
                break;
            case 'today':
                $date = date('Y-m-d');
                $result['entities'] = $this->getInvoiceWhen($date);
                $result['count'] = $this->getInvoiceWhenCount($date);
                break;
            case 'tomorrow':
                $date = date('Y-m-d', mktime(0, 0, 0, date("m"), date('d')+1, date('Y')));
                $result['entities'] = $this->getInvoiceWhen($date);
                $result['count'] = $this->getInvoiceWhenCount($date);
                break;
        }

        return $result;
    }

    /**
     * Returns results for interval future invoice
     * 
     * @param integer $invoiceid Description
     * 
     * @param string  $tab       Description
     * 
     * @return mixed[]
     */
    public function getInfoForTab($invoiceid, $tab)
    {
        $entitie = $this->createQueryBuilder('i');
        switch ($tab) {
            case 'act':
                $entitie
                    ->select('i.dogovorAct')
                    ->addSelect('i.dogovorActDate')
                    ->addSelect('i.dogovorActName')
                    ->addSelect('i.dogovorActOriginal')
                    ->where('i.id = :invoiceid')
                    ->setParameter(':invoiceid', $invoiceid);
                $entitie = $entitie->getQuery()
                    ->getSingleResult();
                break;
            case 'invoice':
                $subQueryCase = '
                    CASE 
                    when customer.name is not NULL 
                    then customer.name 
                    else i.customerName end  as customerName';
                $subQueryCaseTwo = '
                    CASE 
                    when performer.name is not NULL
                    then performer.name 
                    else i.performerName end as performerName';

                $entitie
                    ->select('i.sum')
                    ->addSelect('i.id')
                    ->addSelect('i.invoiceId')
                    ->addSelect('i.date ')
                    ->addSelect('i.dateEnd')
                    ->addSelect('i.dateFact')
                    ->addSelect('i.court')
                    ->addSelect("{$subQueryCase}")
                    ->addSelect("{$subQueryCaseTwo}")
                    ->leftJoin('i.dogovor', 'd')
                    ->leftJoin('d.customer', 'customer')
                    ->leftJoin('d.performer', 'performer')
                    ->where('i.id = :invoiceid')
                    ->setParameter(':invoiceid', (int) $invoiceid);
                $entitie = $entitie->getQuery()
                    ->getSingleResult();
                break;
            case 'contacts':
                $subQueryCase =  $entitie->expr()->andx(
                    $entitie->expr()->eq('mc.modelId', 'customer.id'),
                    $entitie->expr()->eq('mc.modelName', ':text')
                );
                $subQuerySendEmail =  $entitie->expr()->andx(
                    $entitie->expr()->eq('mc.id', 'mcsm.modelContactId')
                );
                $entitie
                    ->Select('mc.id as id')
                    ->addSelect('o.firstName as firstNameOwner')
                    ->addSelect('o.lastName as lastNameOwner')
                    ->addSelect('o.middleName as middleNameOwner')
                    ->addSelect('mc.firstName')
                    ->addSelect('mc.lastName')
                    ->addSelect('mc.middleName')
                    ->addSelect('mc.position')
                    ->addSelect('mc.phone1')
                    ->addSelect('mc.phone2')
                    ->addSelect('mc.email')
                    ->addSelect('mcsm.isSend')
                    ->addSelect('mcsm.id as idIsSend')
                    ->addSelect('mc.birthday')
                    ->addSelect('customer.id as customerId')
                    ->leftJoin('i.customer', 'customer')
                    ->leftJoin('Lists\ContactBundle\Entity\ModelContact', 'mc', 'WITH', $subQueryCase)
                    ->leftJoin('Lists\ContactBundle\Entity\ModelContactSendEmail', 'mcsm', 'WITH', $subQuerySendEmail)
                    ->leftJoin('mc.owner', 'o')
                    ->where('i.id = :invoiceid')
                    ->setParameter(':invoiceid', (int) $invoiceid)
                    ->setParameter(':text', 'organization');
                $entitie = $entitie->getQuery()
                    ->getResult();
                break;
            case 'dogovor':
                $entitie
                    ->Select('customer.name as customerName')
                    ->addSelect('performer.name as performerName')
                    ->addSelect('d.id')
                    ->addSelect('d.number')
                    ->addSelect('d.startdatetime')
                    ->addSelect('d.stopdatetime')
                    ->addSelect('d.prolongation')
                    ->addSelect('d.paymentDeferment')
                    ->addSelect('d.prolongationDate')
                    ->addSelect('d.isActive')
                    ->addSelect('d.subject')
                    ->addSelect('type.name as dogovorTypeId')
                    ->addSelect('d.filepath')
                    ->addSelect('i.dogovorName')
                    ->addSelect('i.dogovorDate')
                    ->addSelect('i.dogovorNumber')
                    ->addSelect('i.delayDays')
                    ->addSelect('i.customerName as customerName_1c')
                    ->addSelect('i.performerName as performerName_1c')
                    ->leftJoin('i.dogovor', 'd')
                    ->leftJoin('d.customer', 'customer')
                    ->leftJoin('d.performer', 'performer')
                    ->leftJoin('d.dogovorType', 'type')
                    ->where('i.id = :invoiceid')
                    ->setParameter(':invoiceid', (int) $invoiceid);
                $entitie = $entitie->getQuery()
                    ->getSingleResult();

                break;
            case 'responsible':
                $entitie
                    ->Select('c.name')
                    ->addSelect('ic.id')
                    ->addSelect('i.id as invoiceId')
                    ->leftJoin('i.invoicecompanystructure', 'ic')
                    ->leftJoin('ic.companystructure', 'c')
                    ->where('i.id = :invoiceid')
                    ->setParameter(':invoiceid', (int) $invoiceid)
                    ->orderBy('c.name');
                $entitie = $entitie->getQuery()
                    ->getResult();
                break;
            case 'customer':
                $entitie
                    ->Select('creator.lastName')
                    ->addSelect('creator.firstName')
                    ->addSelect('customer.createdatetime')
                     ->addSelect('customer.name as customerName')
                    ->addSelect(' i.customerName as customerName1c')
                    ->addSelect('customer.edrpou as customerEdrpou')
                    ->addSelect('i.customerEdrpou as customerEdrpou1c')
                    ->addSelect('customer.shortname')
                    ->addSelect('customer.address')
                    ->addSelect('customer.mailingAddress')
                    ->addSelect('customer.physicalAddress')
                    ->addSelect('customer.inn')
                    ->addSelect('customer.certificate')
                    ->addSelect('customer.shortDescription')
                    ->addSelect('customer.phone')
                    ->addSelect('customer.site')
                    ->addSelect('lookup.name as scope')
                    ->addSelect('c_city.name as cityName')
                    ->addSelect('type.title as customerType')
                    ->addSelect('c_group.name as groupName')
                    ->leftJoin('i.customer', 'customer')
                    ->leftJoin('customer.creator', 'creator')
                    ->leftJoin('customer.scope', 'lookup')
                    ->leftJoin('customer.organizationType', 'type')
                    ->leftJoin('customer.city', 'c_city')
                    ->leftJoin('customer.group', 'c_group')
                    ->where('i.id = :invoiceid')
                    ->setParameter(':invoiceid', (int) $invoiceid);
                $entitie = $entitie->getQuery()
                    ->getSingleResult();
                break;
            case 'history':
                $entitie = '';
                break;
        }

        return $entitie;
    }
}
