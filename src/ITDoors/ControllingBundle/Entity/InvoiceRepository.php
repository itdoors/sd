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
            ->addSelect(
                "("
                . "SELECT SUM(paymens.summa)"
                . " FROM  ITDoorsControllingBundle:InvoicePayments paymens"
                . " WHERE paymens.invoiceId = i.id"
                . ") as paymentsSumma"
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
     * Processes sql query depending on filters
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     * @param mixed[]                    $filters
     */
    public function processFilters(\Doctrine\ORM\QueryBuilder $sql, $filters)
    {
        if (sizeof($filters)) {

            foreach ($filters as $key => $value) {
                if (!$value) {
                    continue;
                }
                switch ($key) {
                    case 'customer':
                        $arr = explode(',', $value);
                        $sql->andWhere("customer.id in (:customerIds)");
                        $sql->setParameter(':customerIds', $arr);
                        break;
                    case 'performer':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $arr = explode(',', $value);
                        $sql->andWhere('performer.id in (:performerIds)');
                        $sql->setParameter(':performerIds', $arr);
                        break;
                    case 'invoiceId':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $arr = explode(',', $value);
                        $sql->andWhere('i.id in (:ids)');
                        $sql->setParameter(':ids', $arr);
                        break;
                    case 'dogovorActName':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $arr = explode(',', $value);
                        $sql->andWhere("i.dogovorActName in (:dogovorActNames)");
                        $sql->setParameter(':dogovorActNames', $arr);
                        break;
                    case 'companystructure':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $arr = explode(',', $value);
                        $sql->leftJoin('i.invoicecompanystructure', 'ics_fil');
                        $sql->leftJoin('ics_fil.companystructure', 'cs_fil');
                        $sql->andWhere("cs_fil.id in (:companystructures)");
                        $sql->setParameter(':companystructures', $arr);
                        break;
                }
            }
        }
    }
    /**
     * Returns results for interval future invoice
     *
     * @param integer $periodmin
     * @param integer $periodmax
     * @param array   $filters
     *
     * @return mixed[]
     */
    public function getInvoicePeriod($periodmin, $periodmax, $filters)
    {
        $res = $this->createQueryBuilder('i');

        /** select */
        $this->selectInvoicePeriod($res);
        /** join */
        $this->joinInvoicePeriod($res);
        /** where */
        $this->whereInvoicePeriod($res, $periodmin, $periodmax);

        $this->processFilters($res, $filters);

        return $res
                ->orderBy('i.customerName', 'ASC')->getQuery();
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
        $res->addSelect('i.dogovorDate')
            ->addSelect('i.performerName');
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
     * @param integer $periodmin
     * @param integer $periodmax
     * @param array   $filters
     *
     * @return integer count
     */
    public function getInvoicePeriodCount($periodmin, $periodmax, $filters)
    {
        $rescount = $this->createQueryBuilder('i');

        /** select */
        $this->selectInvoicePeriodCount($rescount);
        /** where */
        $this->whereInvoicePeriod($rescount, $periodmin, $periodmax);

        /** join */
        $this->joinInvoicePeriod($rescount);

        $this->processFilters($rescount, $filters);

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
     * @param array   $filters
     * 
     * @return mixed[]
     */
    public function getInvoiceCourt($filters)
    {
        $id = 1;

        $res = $this->createQueryBuilder('i');
        /** select */
        $this->selectInvoicePeriod($res);
        /** join */
        $this->joinInvoicePeriod($res);

        $this->processFilters($res, $filters);

        return $res
                ->andWhere("i.court = :id")
                ->setParameter(':id', $id)
                ->orderBy('i.customerName', 'ASC')
                ->getQuery();
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
     * @param array $filters
     * 
     * @return integer count
     */
    public function getInvoiceCourtCount($filters)
    {
        $id = 1;

        $rescount = $this->createQueryBuilder('i');
        /** select */
        $this->selectInvoicePeriodCount($rescount);
        /** join */
        $this->joinInvoicePeriod($rescount);

        $this->processFilters($rescount, $filters);

        return $rescount
                ->andWhere("i.court = :id")
                ->setParameter(':id', $id)
                ->getQuery()
                ->getSingleScalarResult();
    }

    /**
     * Returns results for interval future invoice
     *
     * @param array $filters
     * 
     * @return mixed[]
     */
    public function getInvoicePay($filters)
    {
        $date = date('Y-m-d', time() - 2592000);
        $res = $this->createQueryBuilder('i');
        /** select */
        $this->selectInvoicePeriod($res);
        /** join */
        $this->joinInvoicePeriod($res);
        /** where */
        $this->processFilters($res, $filters);

        $res = $res->andWhere("i.dateFact is not NULL")
            ->andWhere("i.dateFact >= :date")->setParameter(':date', $date)
            ->orderBy('i.customerName', 'ASC');

        return $res->getQuery();
    }
    /**
     * Returns results for interval future invoice
     *
     * @param array $filters
     * 
     * @return mixed[]
     */
    public function getInvoiceFlow($filters)
    {
        $date = date('Y-m-d');
        $res = $this->createQueryBuilder('i');
        /** select */
        $this->selectInvoicePeriod($res);
        /** join */
        $this->joinInvoicePeriod($res);
        /** where */
        $this->processFilters($res, $filters);
        $res = $res
            ->andWhere("i.dateFact is NULL")
            ->andWhere("i.delayDate >= :date")->setParameter(':date', $date)
            ->orderBy('i.customerName', 'ASC');

        return $res->getQuery();
    }
    /**
     * Returns results for interval future invoice
     *
     * @param string $type
     * 
     * @return mixed[]
     */
    public function getInvoiceEmptyData($type)
    {
        $res = $this->createQueryBuilder('i');
        /** select */
        $this->selectInvoicePeriod($res);
        /** join */
        $this->joinInvoicePeriod($res);
        /** where */
        switch ($type) {
            case 'delay':
                $res = $res->andWhere("i.delayDays is NULL or i.delayDays = 0")
                    ->orderBy('i.performerName', 'DESC');
                break;
            case 'act':
                $res = $res->andWhere("i.dogovorActOriginal = :boolen")
                   ->setParameter(':boolen', false, \PDO::PARAM_BOOL)
                    ->orderBy('i.performerName', 'DESC');
                break;
        }

        return $res->getQuery();
    }
    /**
     * Returns results for interval future invoice
     *
     * @param string $type
     * 
     * @return mixed[]
     */
    public function getInvoiceEmptyDataCount($type)
    {
        $res = $this->createQueryBuilder('i');
        /** select */
        $this->selectInvoicePeriodCount($res);
        /** where */
        switch ($type) {
            case 'delay':
                $res = $res->andWhere("i.delayDays is NULL or i.delayDays = 0");
                break;
            case 'act':
                $res = $res->andWhere("i.dogovorActOriginal = :boolen")
                    ->setParameter(':boolen', false, \PDO::PARAM_BOOL);
                break;
        }

        return $res->getQuery()->getSingleScalarResult();
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
    public function getInvoiceFlowSum()
    {
        $date = date('Y-m-d');
        $res = $this->createQueryBuilder('i');
        /** select */
        $this->selectInvoiceSum($res);
        /** join */
        $this->joinInvoicePeriod($res);
        /** where */
        $res = $res->andWhere("i.dateFact is NULL")->andWhere("i.delayDate >= :date")->setParameter(':date', $date);

        return $res->getQuery()->getResult();
    }

    /**
     * Returns results for interval future invoice
     *
     * @param array $filters
     * 
     * @return mixed[]
     */
    public function getInvoicePayCount($filters)
    {
        $date = date('Y-m-d', time() - 2592000);
        $rescount = $this->createQueryBuilder('i');

        /** select */
        $this->selectInvoicePeriodCount($rescount);
        /** join */
        $this->joinInvoicePeriod($rescount);
        /** where */
        $this->processFilters($rescount, $filters);
        $rescount = $rescount
            ->andWhere("i.dateFact is not NULL")
            ->andWhere("i.dateFact >= :date")
            ->setParameter(':date', $date);

        return $rescount->getQuery()->getSingleScalarResult();
    }
    /**
     * Returns results for interval future invoice
     *
     * @param array $filters
     * 
     * @return mixed[]
     */
    public function getInvoiceFlowCount($filters)
    {
        $date = date('Y-m-d');
        $rescount = $this->createQueryBuilder('i');

        /** select */
        $this->selectInvoicePeriodCount($rescount);
        /** join */
        $this->joinInvoicePeriod($rescount);
        /** where */
        $this->processFilters($rescount, $filters);
        $rescount = $rescount
            ->andWhere("i.dateFact is NULL")
            ->andWhere("i.delayDate >= :date")
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
     * @param string $period
     * @param array  $filters
     * 
     * @return mixed[]
     */
    public function getEntittyCountSum($period, $filters=array())
    {
        $result = array();
        switch ($period) {
            case 30:
                $result['entities'] = $this->getInvoicePeriod(1, 30, $filters);
                $result['count'] = $this->getInvoicePeriodCount(1, 30, $filters);
                break;
            case 60:
                $result['entities'] = $this->getInvoicePeriod(31, 60, $filters);
                $result['count'] = $this->getInvoicePeriodCount(31, 60, $filters);
                break;
            case 120:
                $result['entities'] = $this->getInvoicePeriod(61, 120, $filters);
                $result['count'] = $this->getInvoicePeriodCount(61, 120, $filters);
                break;
            case 180:
                $result['entities'] = $this->getInvoicePeriod(121, 180, $filters);
                $result['count'] = $this->getInvoicePeriodCount(121, 180, $filters);
                break;
            case 181:
                $result['entities'] = $this->getInvoicePeriod(181, 0, $filters);
                $result['count'] = $this->getInvoicePeriodCount(181, 0, $filters);
                break;
            case 'court':
                $result['entities'] = $this->getInvoiceCourt($filters);
                $result['count'] = $this->getInvoiceCourtCount($filters);
                break;
            case 'pay':
                $result['entities'] = $this->getInvoicePay($filters);
                $result['count'] = $this->getInvoicePayCount($filters);
                break;
            case 'flow':
                $result['entities'] = $this->getInvoiceFlow($filters);
                $result['count'] = $this->getInvoiceFlowCount($filters);
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
            case 'delay':
                $result['entities'] = $this->getInvoiceEmptyData('delay');
                $result['count'] = $this->getInvoiceEmptyDataCount('delay');
                break;
            case 'act':
                $result['entities'] = $this->getInvoiceEmptyData('act');
                $result['count'] = $this->getInvoiceEmptyDataCount('act');
                break;
        }

        return $result;
    }

    /**
     * Returns results for interval future invoice
     *
     * @param array  $filters
     * 
     * @return mixed[]
     */
    public function getForAnalytic($filters=array())
    {
         $res = $this->createQueryBuilder('i')
            ->select('i.id')
            ->addSelect('i.sum as allSumma')
            ->addSelect('i.delayDate')
            ->addSelect('customer.edrpou')
            ->addSelect(
                "("
                . "SELECT SUM(paymens.summa)"
                . " FROM  ITDoorsControllingBundle:InvoicePayments paymens"
                . " WHERE paymens.invoiceId = i.id"
                . " GROUP BY i.customerEdrpou"
                . ")as paymentsSumma"
            )
//            ->addSelect(
//                "("
//                . "SELECT SUM(i.sum)"
//                . " FROM  ITDoorsControllingBundle:Invoice is"
//                . " WHERE is.id = i.id"
//                . ") as paymentsSumma"
//            )
            ->addSelect('customer.name as customerName')
            ->addSelect('customer.id as customerId')
            ->leftJoin('i.customer', 'customer')
            ->orderBy('customer.name, i.delayDate')
            ->getQuery()->getResult();

        return $res;
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
                    ->addSelect('i.bank')
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
                    $entitie->expr()->orX(
                        $entitie->expr()->eq('mc.modelName', ':text'),
                        $entitie->expr()->eq('mc.modelName', ':textdepartments')
                    )
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
                    ->setParameter(':text', 'organization')
                    ->setParameter(':textdepartments', 'departments');
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
            case 'payments':
                 $entitie
                    ->Select('ip.summa')
                    ->addSelect('ip.date')
                    ->addSelect('ip.bank')
                    ->leftJoin('i.payments', 'ip')
                    ->where('i.id = :invoiceid')
                    ->andWhere('ip.summa is not NULL')
                    ->setParameter(':invoiceid', (int) $invoiceid)
                    ->orderBy('ip.date');
                $entitie = $entitie->getQuery()
                    ->getResult();
                break;
        }

        return $entitie;
    }

    /**
     * Searches organization by $q
     *
     * @param string $q
     *
     * @return mixed[]
     */
    public function getSearchInvoiceIdQuery($q)
    {
        $sql = $this->createQueryBuilder('i')
            ->select('i.invoiceId')
            ->addSelect('i.id')
            ->where('lower(i.invoiceId) LIKE :q')
            ->setParameter(':q', mb_strtolower($q, 'UTF-8') . '%')
            ->getQuery();

        return $sql->getResult();
    }

    /**
     * Searches organization by $q
     *
     * @param string $q
     *
     * @return mixed[]
     */
    public function getSearchDogovorActNameQuery($q)
    {
        $sql = $this->createQueryBuilder('i')
            ->select('i.dogovorActName')
            ->where('lower(i.dogovorActName) LIKE :q')
            ->setParameter(':q', mb_strtolower($q, 'UTF-8') . '%')
            ->getQuery();

        return $sql->getResult();
    }
}
