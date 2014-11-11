<?php

namespace Lists\DogovorBundle\Entity;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

/**
 * DogovorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DogovorRepository extends EntityRepository
{
    /**
     * @var Translator $translator
     */
    protected $translator;

    /**
     * Injects translator
     *
     * @param Translator $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    /**
     * Returns dogovor collection depending on filter
     *
     * @param mixed[] $filters
     * @param int     $id
     *
     * @return Query
     */
    public function getAllForDogovorQuery($filters, $id = null)
    {
        /** @var \Doctrine\ORM\QueryBuilder $sql*/
        $sql = $this->createQueryBuilder('d');

        /** @var \Doctrine\ORM\QueryBuilder $sqlCount */
        $sqlCount = $this->createQueryBuilder('d');

        $this->processSelect($sql);
        $this->processCount($sqlCount);

        $this->processBaseQuery($sql);
        $this->processBaseQuery($sqlCount);

        if ($id) {
            $this->processIdQuery($sql, $id);
            $this->processIdQuery($sqlCount, $id);
        } else {
            $this->processFilters($sql, $filters);
            $this->processFilters($sqlCount, $filters);
        }

        $this->processOrdering($sql);

        $query = $sql->getQuery();

        $count = $sqlCount->getQuery()->getSingleScalarResult();

        $query->setHint('knp_paginator.count', $count);

        return $query;
    }
    /**
     * Returns dogovor collection depending on filter
     *
     * @param integer $id
     *
     * @return Query
     */
    public function getAllForManagerOrganization($id)
    {
        $sql = $this->createQueryBuilder('d')
            ->select;
    }
    /**
     * Returns dogovor collection depending on filter
     *
     * @param integer $idManager manager organization
     *
     * @return Query
     */
    public function getAllDanger($idManager)
    {
        $date = new \DateTime();
        /** @var \Doctrine\ORM\QueryBuilder $sql*/
        $sql = $this->createQueryBuilder('d');
        $sqlCount = $this->createQueryBuilder('d');

        $this->processSelect($sql);
        $this->processCount($sqlCount);

        $this->processBaseQuery($sql);
        $this->processBaseQuery($sqlCount);
        if ($idManager) {
            $sql
                ->leftJoin('o.organizationUsers', 'manager')
                ->andWhere('manager.userId = (:idManager)')
                ->setParameter(':idManager', $idManager);
            $sqlCount
                ->leftJoin('o.organizationUsers', 'manager')
                ->andWhere('manager.userId = (:idManager)')
                ->setParameter(':idManager', $idManager);
        }
        $sql
            ->andWhere(
                'd.stopdatetime is NULL'
                . ' OR '
                . '(d.stopdatetime < :date AND d.isActive = true AND d.prolongationDate is NULL)'
                . ' OR '
                . '(d.isActive = true AND d.prolongationDate is NULL AND d.stopdatetime < :date)'
                . ' OR '
                . '(d.isActive = true AND d.prolongationDate < :date)'
            )
            ->setParameter(':date', $date->modify('-3 month'));
        $sqlCount
            ->andWhere(
                'd.stopdatetime is NULL'
                . ' OR '
                . '(d.stopdatetime < :date AND d.isActive = true AND d.prolongationDate is NULL)'
                . ' OR '
                . '(d.isActive = true AND d.prolongationDate is NULL AND d.stopdatetime < :date)'
                . ' OR '
                . '(d.isActive = true AND d.prolongationDate < :date)'
            )
            ->setParameter(':date', $date->modify('-3 month'));

        $this->processOrdering($sql);

        $query = array(
            'entities' => $sql->getQuery(),
            'count' => $sqlCount->getQuery()->getSingleScalarResult()
        );

        return $query;
    }

    /**
     * Processes sql query. adding select
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processSelect($sql)
    {
        $sql
            ->select('d.id')
            ->addSelect('d.id as dogovorId')
            ->addSelect('d.number as dogovorNumber')
            ->addSelect('d.filepath')
            ->addSelect('d.startdatetime as dogovorStartdatetime')
            ->addSelect('d.stopdatetime as dogovorStopdatetime')
            ->addSelect('d.prolongationDate as dogovorProlongationDate')
            ->addSelect('d.prolongation as dogovorProlongation')
            ->addSelect('o.name as organizationName')
            ->addSelect('o.id as organizationId')
            ->addSelect('customer.name as customerName')
            ->addSelect('customer.id as customerId')
            ->addSelect('performer.name as performerName')
            ->addSelect('performer.id as performerId')
            ->addSelect('d.isActive as dogovorIsActive')
            ->addSelect('d.subject as dogovorSubject')
            ->addSelect('type.name as dogovorType');
    }

    /**
     * Processes sql query. adding select
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processCount($sql)
    {
        $sql
            ->select('COUNT(d.id) as dogovorcount');

    }

    /**
     * Processes sql query. adding base query
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processBaseQuery($sql)
    {
        $sql
            ->leftJoin('d.organization', 'o')
            ->leftJoin('d.customer', 'customer')
            ->leftJoin('d.performer', 'performer')
            ->leftJoin('d.dogovorType', 'type');
    }

    /**
     * Processes sql query. adding id query
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     * @param int                        $id
     */
    public function processIdQuery($sql, $id)
    {
        $sql
            ->andWhere('d.id = :id')
            ->setParameter(':id', $id);
    }

    /**
     * Processes sql query. adding users query
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processOrdering($sql)
    {
        $sql
            ->orderBy('o.name', 'ASC');
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
                    case 'organization':
                        $sql
                            ->andWhere("o.id IN (:organizationIds)");

                        $ids = explode(',', $value);

                        $sql->setParameter(':organizationIds', $ids);
                        break;
                    case 'customer':
                        $sql
                            ->andWhere("customer.id IN (:customerIds)");

                        $ids = explode(',', $value);

                        $sql->setParameter(':customerIds', $ids);
                        break;
                    case 'performer':
                        $sql
                            ->andWhere("performer.id IN (:performerIds)");

                        $ids = explode(',', $value);

                        $sql->setParameter(':performerIds', $ids);
                        break;
                    case 'prolongation':
                        $valueBool = $value == 'Yes' ? 'TRUE': 'FALSE';
                        $sql
                            ->andWhere("d.prolongation = {$valueBool}");

                        break;
                    case 'dogovorType':
                        $ids = explode(',', $value);
                        $sql
                            ->andWhere("d.dogovorTypeId in (:dogovorTypeId)")
                            ->setParameter(':dogovorTypeId', $ids);
                        break;
                    case 'number':
                        $value = trim($value);

                        $sql
                            ->andWhere("d.number LIKE :number")
                            ->setParameter(':number', "{$value}%");
                        break;
                    case 'dateRange':
                        $dateArr = explode('-', $value);
                            $dateStart = new \DateTime(str_replace('.', '-', $dateArr[0]));
                            $dateStop = new \DateTime(str_replace('.', '-', $dateArr[1]));
                            $sql
                                ->andWhere("d.createDateTime BETWEEN :datestart AND :datestop")
                                ->setParameter(':datestart', $dateStart)
                                ->setParameter(':datestop', $dateStop);
                        break;
                    case 'dateRangeForType':
                        $dateArr = explode('-', $value);
                        $dateStart = new \DateTime(str_replace('.', '-', $dateArr[0]));
                        $dateStop = new \DateTime('23:59:59 '.str_replace('.', '-', $dateArr[1]));
                        if (isset($filters['typeDate']) && $filters['typeDate'] == 'startdatetime') {
                            $sql->andWhere("d.startdatetime BETWEEN :datestart AND :datestop");
                        } elseif (isset($filters['typeDate']) && $filters['typeDate'] == 'stopdatetime') {
                            $sql->andWhere("d.stopdatetime BETWEEN :datestart AND :datestop");
                        } elseif (isset($filters['typeDate']) && $filters['typeDate'] == 'prologation') {
                            $sql->andWhere("d.prolongationDate BETWEEN :datestart AND :datestop");
                        } else {
                            $sql->andWhere(
                                "
                                    d.startdatetime BETWEEN :datestart AND :datestop
                                    or
                                    d.stopdatetime BETWEEN :datestart AND :datestop
                                    or
                                    d.prolongationDate BETWEEN :datestart AND :datestop
                                "
                            );
                        }
                        $sql->setParameter(':datestart', $dateStart)
                            ->setParameter(':datestop', $dateStop);
                        break;
                    case 'subject':
                            $sql
                                ->andWhere("d.subject LIKE :subject")
                                ->setParameter(':subject', "{$value}%");
                        break;

                }
            }
        }
    }

    /**
     * Return dogovor show info by id
     *
     * @param int $id
     *
     * @return mixed[]
     */
    public function getDogovorById($id)
    {
        return $this->createQueryBuilder('d')
            ->select('d.id as id')
            ->addSelect('d.createDateTime')
            ->addSelect('d.number as number')
            ->addSelect('d.subject as subject')
            ->addSelect('d.filepath as filepath')
            ->addSelect('d.prolongation as prolongation')
            ->addSelect('d.prolongationTerm as prolongationTerm')
            ->addSelect('d.startdatetime as startdatetime')
            ->addSelect('d.stopdatetime as stopdatetime')
            ->addSelect('d.isActive as isActive')
            ->addSelect('d.mashtab as mashtab')
            ->addSelect('dogovorType.name as type')
            ->addSelect('customer.id as customerId')
            ->addSelect('performer.id as performerId')
            ->addSelect('organization.id as organizationId')
            ->addSelect('city.id as cityId')
            ->addSelect('customer.name as customerName')
            ->addSelect('performer.name as performerName')
            ->addSelect("CONCAT(CONCAT(saller.lastName, ' '), saller.firstName) as sallerName")
            ->addSelect('organization.name as organizationName')
            ->addSelect('city.name as cityName')
            ->addSelect('creator.firstName as creatorFirstName')
            ->addSelect('creator.lastName as creatorLastName')
            ->addSelect('creator.middleName as creatorMiddleName')
            ->leftJoin('d.saller', 'saller')
            ->leftJoin('d.customer', 'customer')
            ->leftJoin('d.performer', 'performer')
            ->leftJoin('d.organization', 'organization')
            ->leftJoin('d.city', 'city')
            ->leftJoin('d.user', 'creator')
            ->leftJoin('d.dogovorType', 'dogovorType')
            ->where('d.id = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * Returns choices for is active
     *
     * @return mixed[]
     */
    public function getIsActiveChoices()
    {
        return array(
            'No' => $this->translator->trans("No", array(), 'messages'),
            'Yes' => $this->translator->trans("Yes", array(), 'messages')
        );
    }

    /**
     * Returns choices for prolongation
     *
     * @return mixed[]
     */
    public function getProlongationChoices()
    {
        return array(
            'No' => $this->translator->trans("No", array(), 'messages'),
            'Yes' => $this->translator->trans("Yes", array(), 'messages')
        );
    }

    /**
     * Returns choices for mashtab
     *
     * @return mixed[]
     */
    public function getMashtabChoices()
    {
        return array(
            'm_local' => $this->translator->trans("Local", array(), 'ListsDogovorBundle'),
            'm_global' => $this->translator->trans("Global", array(), 'ListsDogovorBundle')
        );
    }

    /**
     * Return dogovor show info by id
     *
     * @param integer $id Organization.id
     *
     * @return mixed[]
     */
    public function getDogovorByOrganizationId($id)
    {
        /** @var \Doctrine\ORM\QueryBuilder $sql*/
        $sql = $this->createQueryBuilder('d');

        $this->processSelect($sql);

        $this->processBaseQuery($sql);

        $this->processOrdering($sql);

        $query = $sql->where('d.organizationId = :organizationId')
            ->setParameter(':organizationId', $id)
            ->getQuery()->getResult();

        return $query;
    }
    /**
     * Return dogovor show info by id
     *
     * @param integer $id Organization.id
     *
     * @return mixed[]
     */
    public function getDogovorsForContractorId($id)
    {
        /** @var \Doctrine\ORM\QueryBuilder $sql*/
        $sql = $this->createQueryBuilder('d')
                ->select('d.id')
                ->addSelect('u.firstName')
                ->addSelect('u.lastName')
                ->addSelect('u.middleName')
                ->addSelect('d.createDateTime')
                ->addSelect('d.filepath')
                ->addSelect('dogovorType.name as type')
                ->leftJoin('d.user', 'u')
                ->leftJoin('d.dogovorType', 'dogovorType')
                ->where('d.organizationId = :organizationId')
                ->setParameter(':organizationId', $id)
                ->getQuery()
                ->getResult();

        return $sql;
    }

    /**
     * Return dogovor show info by id
     *
     * @param integer $id Organization.id
     *
     * @return mixed[]
     */
    public function getDogovorByOrganizationCustomerPerformerId($id)
    {
        /** @var \Doctrine\ORM\QueryBuilder $sql*/
        $sql = $this->createQueryBuilder('d');

        $this->processSelect($sql);

        $this->processBaseQuery($sql);

        $this->processOrdering($sql);

        $sql
            ->where('d.organizationId = :oId or d.customerId = :oId or d.performerId = :oId')
            ->setParameter(':oId', $id);

        $query = $sql
            ->getQuery()->getResult();

        return $query;
    }
}
