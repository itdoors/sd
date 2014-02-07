<?php

namespace Lists\DogovorBundle\Entity;

use Doctrine\ORM\EntityRepository;
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

        if ($id)
        {
            $this->processIdQuery($sql, $id);
            $this->processIdQuery($sqlCount, $id);
        }
        else
        {
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
     * Processes sql query. adding select
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     */
    public function processSelect($sql)
    {
        $sql
            ->select('d.id as dogovorId')
            ->addSelect('d.number as dogovorNumber')
            ->addSelect('d.startdatetime as dogovorStartdatetime')
            ->addSelect('d.stopdatetime as dogovorStopdatetime')
            ->addSelect('d.prolongationDate as dogovorProlongationDate')
            ->addSelect('d.prolongation as dogovorProlongation')
            ->addSelect('o.name as organizationName')
            ->addSelect('customer.name as customerName')
            ->addSelect('performer.name as performerName')
            ->addSelect('d.isActive as dogovorIsActive')
            ->addSelect('d.subject as dogovorSubject')
            ->addSelect('type.name as dogovorType')
        ;
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
            ->leftJoin('d.dogovorType', 'type')
        ;
            /*->leftJoin('o.city', 'c')
            ->leftJoin('c.region', 'r');*/
    }

    /**
     * Processes sql query. adding id query
     *
     * @param \Doctrine\ORM\QueryBuilder $sql
     * @param int $id
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
     * @param mixed[] $filters
     */
    public function processFilters(\Doctrine\ORM\QueryBuilder $sql, $filters)
    {
        if (sizeof($filters))
        {

            foreach($filters as $key => $value)
            {
                if (!$value)
                {
                    continue;
                }
                switch($key)
                {
                    case 'organization':
                        $sql
                            ->andWhere("o.id = :organizationId");

                        $sql->setParameter(':organizationId', $value);
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
            ->addSelect('organization.name as organizationName')
            ->addSelect('city.name as cityName')
            ->leftJoin('d.customer', 'customer')
            ->leftJoin('d.performer', 'performer')
            ->leftJoin('d.organization', 'organization')
            ->leftJoin('d.city', 'city')
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
}
