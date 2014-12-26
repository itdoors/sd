<?php

namespace Lists\OrganizationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BankRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BankRepository extends EntityRepository
{
    /**
     * Searches bank by $q
     *
     * @param string  $q
     * @param string  $field
     *
     * @return mixed[]
     */
    public function getSearchQuery($q, $field)
    {
        $sql = $this->createQueryBuilder('b');

        $sql->where($sql->expr()->like('lower(:field)', ':q'))
            ->setParameter(':q', '%' . mb_strtolower($q, 'UTF-8') . '%')
            ->setParameter(':field', $field);

        return $sql->getQuery()->getResult();
    }
    /**
     * Searches bank by $q
     *
     * @param string  $q
     *
     * @return mixed[]
     */
    public function getSearchNameAndMfoQuery($q)
    {
        $sql = $this->createQueryBuilder('b');

        $sql->where(
            $sql->expr()->orX(
                $sql->expr()->like('lower(b.name)', ':q'),
                $sql->expr()->like('lower(b.mfo)', ':q')
            )
        )
        ->setParameter(':q', '%' . mb_strtolower($q, 'UTF-8') . '%')
        ->groupBy('b.id');

        return $sql->getQuery()->getResult();
    }
    /**
     * Searches organization by $q
     *
     * @param integer $organizationId
     *
     * @return mixed[]
     */
    public function getBanks($organizationId)
    {
        $sql = $this->createQueryBuilder('b')
            ->select('b.mfo')
            ->addSelect('b.name')
            ->addSelect('b.id')
            ->addSelect('a.name as currentAccount')
            ->innerJoin('b.currentAccounts', 'a')
            ->where('a.organization = :organizationId')
            ->setParameter(':organizationId', $organizationId);

        return $sql->getQuery()->getResult();
    }
}