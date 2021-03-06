<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ServiceProjectStateTenderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ServiceProjectStateTenderRepository extends EntityRepository
{
    /**
     * Searches service by $q
     *
     * @param string  $q
     *
     * @return mixed[]
     */
    public function getSearchQuery($q)
    {
        $sql = $this->createQueryBuilder('s');

        $sql
            ->where($sql->expr()->like('lower(s.name)', ':q'))
            ->setParameter(':q', '%' . mb_strtolower($q, 'UTF-8') . '%');

        return $sql->getQuery()->getResult();
    }
}
