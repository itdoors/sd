<?php

namespace Lists\OrganizationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * OrganizationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrganizationRepository extends EntityRepository
{
    /**
     * @param int[]   $userIds
     * @param mixed[] $filters
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllForManagerQuery ($userIds, $filters)
    {
        if (!is_array($userIds) && $userIds) {
            $userIds = array ($userIds);
        }

        /** @var \Doctrine\ORM\QueryBuilder $sql */
        $sql = $this->createQueryBuilder('o');

        $this->processSelect($sql);

        $this->processBaseQuery($sql);
        $sql->where('o.organizationSignId != 61 or o.organizationSignId is NULL')
            ->andWhere('role.lukey = :lukey')
            ->setParameter(':lukey', 'manager_organization');

        if (sizeof($userIds)) {
            $this->processUserQuery($sql, $userIds);
        }

        $this->processFilters($sql, $filters);

        $this->processOrdering($sql);

        $query = $sql->getQuery();

        return $query;
    }
    /**
     * @param int[]   $userIds
     * @param mixed[] $filters
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllForSalesQuery ($userIds, $filters)
    {
        if (!is_array($userIds) && $userIds) {
            $userIds = array ($userIds);
        }

        /** @var \Doctrine\ORM\QueryBuilder $sql */
        $sql = $this->createQueryBuilder('o');

        /** @var \Doctrine\ORM\QueryBuilder $sqlCount */
        $sqlCount = $this->createQueryBuilder('o');

        $this->processSelect($sql);
        $this->processCount($sqlCount);

        $this->processBaseQuery($sql);
        //$sql->where('o.organizationSignId != 61 or o.organizationSignId is NULL');
        $this->processBaseQuery($sqlCount);
        //$sqlCount->where('o.organizationSignId != 61 or o.organizationSignId is NULL');

        if (sizeof($userIds)) {
            $this->processUserQuery($sql, $userIds);
            $this->processUserQuery($sqlCount, $userIds);
        }

        $this->processFilters($sql, $filters);
        $this->processFilters($sqlCount, $filters);

        $this->processOrdering($sql);

        $query = $sql->getQuery();

        $count = $sqlCount->getQuery()->getSingleScalarResult();

        $query->setHint('knp_paginator.count', $count);

        return $query;
    }
    /**
     * @param int[]   $userIds
     * @param mixed[] $filters
     *
     * @return \Doctrine\ORM\Query
     */
    public function getCompetitors ($userIds, $filters)
    {
        if (!is_array($userIds) && $userIds) {
            $userIds = array ($userIds);
        }

        /** @var \Doctrine\ORM\QueryBuilder $sql */
        $sql = $this->createQueryBuilder('o');

        /** @var \Doctrine\ORM\QueryBuilder $sqlCount */
        $sqlCount = $this->createQueryBuilder('o');

        $this->processSelect($sql);
        $this->processCount($sqlCount);

        $this->processBaseQuery($sql);
        $sql->where('o.organizationSignId = 61');
        $this->processBaseQuery($sqlCount);
        $sqlCount->where('o.organizationSignId = 61');

        if (sizeof($userIds)) {
            $this->processUserQuery($sql, $userIds);
            $this->processUserQuery($sqlCount, $userIds);
        }

        $this->processFilters($sql, $filters);
        $this->processFilters($sqlCount, $filters);

        $this->processOrdering($sql);

        $query = $sql->getQuery();

        $count = $sqlCount->getQuery()->getSingleScalarResult();

        $query->setHint('knp_paginator.count', $count);

        return $query;
    }
    /**
     * @param integer $userIds
     * @param integer $signId
     * @param mixed[] $filters
     *
     * @return \Doctrine\ORM\Query
     */
    public function getContractor ($userIds, $signId, $filters)
    {
        if (!is_array($userIds) && $userIds) {
            $userIds = array ($userIds);
        }

        /** @var \Doctrine\ORM\QueryBuilder $sql */
        $sql = $this->createQueryBuilder('o');

        /** @var \Doctrine\ORM\QueryBuilder $sqlCount */
        $sqlCount = $this->createQueryBuilder('o');

        $this->processSelect($sql);
        $this->processCount($sqlCount);

        $this->processBaseQuery($sql);
        $sql
            ->where('o.organizationSignId = :organizationSignId')
            ->setParameter(':organizationSignId', $signId);
        $this->processBaseQuery($sqlCount);
        $sqlCount->where('o.organizationSignId = :organizationSignId')
            ->setParameter(':organizationSignId', $signId);

        if (sizeof($userIds)) {
            $this->processUserQuery($sql, $userIds);
            $this->processUserQuery($sqlCount, $userIds);
        }

        $this->processFilters($sql, $filters);
        $this->processFilters($sqlCount, $filters);

        $this->processOrdering($sql);

        $query = $sql->getQuery();

        $count = $sqlCount->getQuery()->getSingleScalarResult();

        $query->setHint('knp_paginator.count', $count);

        return $query;
    }
    /**
     * @param integer $signId
     *
     * @return mixed[]
     */
    public function getAllContractors ($signId)
    {
        /** @var \Doctrine\ORM\QueryBuilder $sql */
        $sql = $this->createQueryBuilder('o');

        /** @var \Doctrine\ORM\QueryBuilder $sqlCount */
        $sqlCount = $this->createQueryBuilder('o');

        $this->processSelect($sql);
        $sql->addSelect("CONCAT(CONCAT(creator.lastName, ' '), creator.firstName) as creatorName");
        $sql->addSelect(
            "array_to_string(
               ARRAY(
                    SELECT k.name FROM ListsOrganizationBundle:KvedOrganization k_o
                    LEFT JOIN ListsOrganizationBundle:Kved k WITH k_o.kved = k
                    WHERE k_o.organization = o
               ),
            ', ') as kveds"
        );
        //$sql->leftJoin('ListsOrganizationBundle:KvedOrganization', 'k_o', 'WITH', 'k_o.organization = o');
        //$sql->leftJoin('k_o.kved', 'k');


        $this->processBaseQuery($sql);
        $sql
            ->where('o.organizationSignId = :organizationSignId')
            ->setParameter(':organizationSignId', $signId);
        $sqlCount->where('o.organizationSignId = :organizationSignId')
            ->setParameter(':organizationSignId', $signId);

        $this->processOrdering($sql);

        $query = $sql->getQuery();

        $result = $query->getArrayResult();

        return $result;
    }
    /**
     * Processes sql query. adding select
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processSelect ($sql)
    {
        $sql
            ->select('DISTINCT(o.id) as organizationId', 'o.name as organizationName')
            ->addSelect('o.edrpou')
            ->addSelect('o.shortname as organizationShortname')
            ->addSelect('ownership.name as ownershipName')
            ->addSelect('ownership.shortname as ownershipShortname')
            ->addSelect('creator.id as creatorId')
            ->addSelect('view.name as viewName')
            ->addSelect('c.name as cityName')
            ->addSelect('r.name as regionName')
            ->addSelect('oUser.userId as managerId')
            ->addSelect('scope.name as scopeName')
            ->addSelect(
                "array_to_string(
                    ARRAY(
                        SELECT
                            CONCAT(CONCAT(u.lastName, ' '), u.firstName)
                        FROM
                            SDUserBundle:User u
                        LEFT JOIN u.organizationUsers ou
                        WHERE ou.organizationId = o.id
                    ), ','
                ) as fullNames"
            )
            ->addSelect(
                "array_to_string(
                    ARRAY(
                        SELECT
                            os.name
                        FROM
                            ListsOrganizationBundle:Organization o2
                        LEFT JOIN o2.organizationsigns os
                        WHERE o2.id = o.id
                    ), ','
                ) as viewNames"
            );
    }
    /**
     * Processes sql query. adding select
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processCount ($sql)
    {
        $sql->select('COUNT(DISTINCT o.id) as orgcount');
    }
    /**
     * Processes sql query. adding base query
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processBaseQuery ($sql)
    {
        $subQueryCase = $sql->expr()->andx(
            $sql->expr()->eq('view.id', 'o.organizationSignId')
        );
        $sql
            ->leftJoin('o.city', 'c')
            ->leftJoin('c.region', 'r')
            ->leftJoin('o.scope', 'scope')
            ->leftJoin('o.organizationUsers', 'oUser')
            ->leftJoin('oUser.user', 'users')
            ->leftJoin('oUser.role', 'role')
            ->leftJoin('o.ownership', 'ownership')
            ->leftJoin('Lists\LookupBundle\Entity\Lookup', 'view', 'WITH', $subQueryCase)
            ->leftJoin('o.creator', 'creator')
            ->andWhere('o.parent_id is null');
    }
    /**
     * Processes sql query. adding users query
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     * @param int[]                      $userIds
     */
    public function processUserQuery ($sql, $userIds)
    {
        $sql
            ->andWhere('users.id in (:userIds)')
            ->setParameter(':userIds', $userIds);
    }
    /**
     * Processes sql query. adding users query
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processOrdering ($sql)
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
    public function processFilters (\Doctrine\ORM\QueryBuilder $sql, $filters)
    {
        if (sizeof($filters)) {

            foreach ($filters as $key => $value) {
                if (!$value) {
                    continue;
                }
                switch ($key) {
                    case 'organization':
                        $sql
                            ->andWhere("o.id in (:organizationId)");

                        $sql->setParameter(':organizationId', explode(',', $value));
                        break;
                    case 'scope':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('scope.id in (:scopeIds)');
                        $sql->setParameter(':scopeIds', $value);
                        break;
                    case 'city':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('c.id in (:cityIds)');
                        $sql->setParameter(':cityIds', $value);
                        break;
                    case 'region':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('r.id in (:regionIds)');
                        $sql->setParameter(':regionIds', $value);
                        break;
                    case 'users':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('users.id in (:userFilterIds)');
                        $sql->setParameter(':userFilterIds', $value);
                        break;
                    case 'user':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('users.id in (:userFilterIds)');
                        $sql->setParameter(':userFilterIds', $value);
                        break;
                    case 'organizationEdrpou':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('o.edrpou in (:edrpou)');
                        $sql->setParameter(':edrpou', explode(',', $value));
                        break;
                    case 'organizationsigns':
                        $value = explode(',', $value);
                        $sql->andWhere(
                            'o.id in (
                                SELECT
                                    o3.id
                                FROM
                                    ListsOrganizationBundle:Organization o3
                                LEFT JOIN o3.organizationsigns os1
                                WHERE o3.id = o.id
                                AND os1.id in (:organizationsigns)
                            )'
                        );
                        $sql->setParameter(':organizationsigns', $value);
                        break;
                    /* case 'users':
                      if (isset($value[0]) && !$value[0]) {
                      break;
                      }
                      $query->andWhereIn('ou.user_id', $value);
                      break; */
                }
            }
        }
    }
    /**
     * Searches organization by $q
     *
     * @param string $q
     * @param int    $organizationSignId
     *
     * @return mixed[]
     */
    public function getSearchQuery ($q, $organizationSignId = null)
    {
        $sql = $this->createQueryBuilder('o')
            ->where('lower(o.name) LIKE :q')
            ->andWhere('o.parent_id is null')
            ->setParameter(':q', '%' . mb_strtolower($q, 'UTF-8') . '%');

        if ($organizationSignId) {
            $sql->andWhere('o.organizationSignId = :organizationSignId')
                ->setParameter(':organizationSignId', $organizationSignId);
        }

        return $sql->getQuery()->getResult();
    }
    /**
     * Searches organization by $q
     *
     * @param string $q
     *
     * @return mixed[]
     */
    public function getSearchContactsQuery ($q)
    {
        $sql = $this->createQueryBuilder('o')
            ->select('DISTINCT(o.id) as organizationId')
            ->addSelect('o.edrpou as organizationEdrpou')
            ->addSelect('o.name as organizationName')
            ->addSelect('o.shortname as organizationShortName')
            ->addSelect(
                "
                    array_to_string(
                       ARRAY(
                          SELECT
                            CONCAT(CONCAT(u.lastName, ' '), u.firstName)
                          FROM
                            SDUserBundle:User u
                          INNER JOIN u.organizationUsers ou
                          WHERE ou.organizationId = o.id
                       ), ', '
                     ) as fullNames
                    "
            )
            ->leftJoin('o.organizationUsers', 'oUser')
            ->leftJoin('oUser.user', 'users')
            ->where('lower(o.name) LIKE :q OR lower(o.shortname) LIKE :q')
            ->andWhere('o.parent_id is null')
            ->setParameter(':q', '%' . mb_strtolower($q, 'UTF-8') . '%')
            ->getQuery();

        return $sql->getResult();
    }
    /**
     * @param string $edrpou
     *
     * @return array
     */
    public function findByEdrpou ($edrpou)
    {
        return $this->createQueryBuilder('o')
                ->where('o.edrpou = :edrpou')
                ->andWhere('o.parent_id is null')
                ->setParameter(':edrpou', $edrpou)
                ->getQuery()
                ->getResult();
    }
    /**
     * @param string $edrpou
     *
     * @return array
     */
    public function findEdrpou ($edrpou)
    {
        return $this->createQueryBuilder('o')
                ->where('o.edrpou LIKE :edrpou')
                ->andWhere('o.parent_id is null')
                ->setParameter(':edrpou', $edrpou . '%')
                ->getQuery()
                ->getResult();
    }
    /**
     * Returns organization ids with in one organization group
     *
     * @param int $organizationId
     *
     * @return array
     */
    public function getIdsInGroup ($organizationId)
    {
        $organization = $this->getEntityManager()->getRepository('ListsOrganizationBundle:Organization')
            ->find($organizationId);

        if (!$organization || !$organization->getGroupId()) {
            return array ();
        }

        $sql = $this->createQueryBuilder('o')
            ->select('o.id as id')
            ->where('o.group_id = :groupId')
            ->andWhere('o.parent_id is null')
            ->setParameter(':groupId', $organization->getGroupId())
            ->getQuery()
            ->getResult();

        return $sql;
    }
    /**
     * Searches organization by $q
     *
     * @param string $q
     *
     * @return mixed[]
     */
    public function searchSelfOrganization ($q)
    {
        $sql = $this->createQueryBuilder('o')
            ->leftJoin('o.role', 'l')
            ->where('lower(o.name) LIKE :q')
            ->setParameter(':q', '%' . mb_strtolower($q, 'UTF-8') . '%')
            ->andWhere('l.lukey = :key')
            ->setParameter(':key', 'organization_sign_own')
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
    public function searchOrganizationFirst ($q)
    {
        $sql = $this->createQueryBuilder('o')
            ->where('lower(o.name) LIKE :q')
            ->setParameter(':q', mb_strtolower($q, 'UTF-8') . '%')
            ->getQuery();

        return $sql->getResult();
    }
    /**
     * Returns results for interval future invoice
     * 
     * @return mixed[]
     */
    public function getForInvoice ()
    {
        $res = $this->createQueryBuilder('o')
                ->select('o.id')
                ->addSelect('o.edrpou')
                ->addSelect(
                    "array_to_string(
                        ARRAY(
                            SELECT
                                DISTINCT(cs.name)
                            FROM ITDoorsControllingBundle:Invoice  i_company
                            LEFT JOIN ITDoorsControllingBundle:InvoiceCompanystructure ics 
                                WITH ics.invoiceId = i_company.id
                            LEFT JOIN ics.companystructure  cs
                                WHERE o.id = i_company.customerId
                        ), ','
                    ) as responsibles"
                )
                ->addSelect(
                    "(
                SELECT SUM(paymens.summa)
                FROM  ITDoorsControllingBundle:Invoice  i_paymens
                LEFT JOIN  ITDoorsControllingBundle:InvoicePayments paymens
                WHERE paymens.invoiceId = i_paymens.id
                AND i_paymens.customerId = o.id
                )as paymentsSumma"
                )
                ->addSelect(
                    '(
                  SELECT SUM(i_s.sum)
                  FROM  ITDoorsControllingBundle:Invoice  i_s
                  WHERE i_s.customerId = o.id
                 ) as allSummaInvoice'
                )
                ->addSelect(
                    '(
                        SELECT SUM(i_a_d.summa)
                        FROM  ITDoorsControllingBundle:Invoice  i_s_ 
                        LEFT JOIN ITDoorsControllingBundle:InvoiceAct i_a WITH i_s_.id = i_a.invoiceId
                        LEFT JOIN ITDoorsControllingBundle:InvoiceActDetal i_a_d WITH i_a.id = i_a_d.invoiceActId
                        WHERE i_s_.customerId = o.id
                    ) as allSumma'
                )
                ->addSelect('o.name as customerName')
                ->where(
                    'o.id in (
                        SELECT DISTINCT(i.customerId) 
                        FROM  ITDoorsControllingBundle:Invoice i
                    )'
                )
                ->orderBy('allSumma')
                //->setMaxResults(40)
                ->getQuery()->getResult();

        return $res;
    }
    /**
     * Returns results for interval future invoice
     * 
     * @param array $filters
     * 
     * @return mixed[]
     */
    public function getForInvoiceAndCount ($filters)
    {
        $res = $this->createQueryBuilder('o')
            ->select('o.id')
            ->addSelect('o.name as customerName')
            ->where(
                'o.id in (
                    SELECT DISTINCT(i.customerId) 
                    FROM  ITDoorsControllingBundle:Invoice i
                )'
            )
            ->orderBy('o.name');

        $resCount = $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->where(
                'o.id in (
                    SELECT DISTINCT(i.customerId) 
                    FROM  ITDoorsControllingBundle:Invoice i
                )'
            );

        if (sizeof($filters)) {

            foreach ($filters as $key => $value) {
                if (!$value) {
                    continue;
                }
                switch ($key) {
                    case 'customer':
                        $arr = explode(',', $value);
                        $res->andWhere("o.id in (:customerIds)");
                        $res->setParameter(':customerIds', $arr);
                        $resCount->andWhere("o.id in (:customerIds)");
                        $resCount->setParameter(':customerIds', $arr);
                        break;
                    case 'performer':
                        $arr = explode(',', $value);
                        $res->andWhere(
                            'o.id in (
                                SELECT DISTINCT(i_p.customerId) 
                                FROM  ITDoorsControllingBundle:Invoice i_p
                                WHERE i_p.performerId in (:performerIds)
                            )'
                        );
                        $res->setParameter(':performerIds', $arr);
                        $resCount->andWhere(
                            'o.id in (
                                SELECT DISTINCT(i_p.customerId) 
                                FROM  ITDoorsControllingBundle:Invoice i_p
                                WHERE i_p.performerId in (:performerIds)
                            )'
                        );
                        $resCount->setParameter(':performerIds', $arr);
                        break;
                    case 'dateRange':
                        $dateArr = explode('-', $value);
                        $dateStart = new \DateTime(str_replace('.', '-', $dateArr[0]));
                        $dateStop = new \DateTime(str_replace('.', '-', $dateArr[1]));

                        $res
                            ->andWhere(
                                'o.id in (
                                    SELECT DISTINCT(i_date.customerId) 
                                    FROM  ITDoorsControllingBundle:Invoice i_date
                                    WHERE i_date.date BETWEEN :datestart AND :datestop
                                )'
                            )
                            ->setParameter(':datestart', $dateStart)
                            ->setParameter(':datestop', $dateStop);
                        $resCount
                            ->andWhere(
                                'o.id in (
                                SELECT DISTINCT(i_date.customerId) 
                                FROM  ITDoorsControllingBundle:Invoice i_date
                                WHERE i_date.date BETWEEN :datestart AND :datestop
                                )'
                            )
                            ->setParameter(':datestart', $dateStart)
                            ->setParameter(':datestop', $dateStop);
                        break;
                }
            }
        }
        $result = array (
            'entity' => $res->getQuery(),
            'count' => $resCount->getQuery()->getSingleScalarResult()
        );

        return $result;
    }
    /**
     * Returns results for interval future invoice
     * 
     * @return mixed[]
     */
    public function getForInvoiceAct ()
    {
        $res = $this->createQueryBuilder('o')
            ->select('o.id')
            ->addSelect('o.edrpou')
            ->addSelect(
                "(
                SELECT SUM(paymens.summa)
                FROM  ITDoorsControllingBundle:Invoice  i_paymens
                LEFT JOIN  ITDoorsControllingBundle:InvoicePayments paymens
                WHERE paymens.invoiceId = i_paymens.id
                AND i_paymens.customerId = o.id
                )as paymentsSumma"
            )
            ->addSelect(
                "array_to_string(
                  ARRAY(
                        SELECT
                          DISTINCT(cs.name)
                        FROM ITDoorsControllingBundle:Invoice  i_company
                        LEFT JOIN ITDoorsControllingBundle:InvoiceCompanystructure ics
                            WITH ics.invoiceId = i_company.id
                        LEFT JOIN ics.companystructure  cs
                            WHERE o.id = i_company.customerId
                      ), ','
                 ) as responsibles"
            )
            ->addSelect(
                '(
                  SELECT SUM(i_s.sum)
                  FROM  ITDoorsControllingBundle:Invoice  i_s
                  WHERE i_s.customerId = o.id
                 ) as allSummaInvoice'
            )
            ->addSelect(
                '(
                  SELECT SUM(i_a_d.summa)
                  FROM  ITDoorsControllingBundle:Invoice  i_s_ 
                  LEFT JOIN ITDoorsControllingBundle:InvoiceAct i_a WITH i_s_.id = i_a.invoiceId
                  LEFT JOIN ITDoorsControllingBundle:InvoiceActDetal i_a_d WITH i_a.id = i_a_d.invoiceActId
                  WHERE i_s_.customerId = o.id
                 ) as allSumma'
            )
            ->addSelect('o.name as customerName')
            ->where(
                'o.id in (
                    SELECT DISTINCT(i.customerId) 
                    FROM  ITDoorsControllingBundle:Invoice i
                    LEFT JOIN ITDoorsControllingBundle:InvoiceAct i_a_
                    WHERE i.id = i_a_.invoiceId
                    AND i_a_.original = false
                )'
            )
            ->orderBy('allSumma')
            ->getQuery()
            ->getResult();

        return $res;
    }
}
