<?php

namespace Lists\MpkBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * MpkRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MpkRepository extends EntityRepository
{

    /**
     * Searches mpk by $q
     *
     * @param string $q
     *
     * @return mixed[]
     */
    public function getSearchQueryMpk($q)
    {
        $sql = $this->createQueryBuilder('c')
            ->where('lower(c.name) LIKE :q')
            ->setParameter(':q', mb_strtolower($q, 'UTF-8') . '%')
            ->getQuery();

        return $sql->getResult();
    }

    /**
     * Searches mpk by text and from fixed department
     *
     * @param string  $q
     * @param integer $id
     *
     * @return mixed[]
     */
    public function getDepartmentPeopleQueryMpk($q, $id = null)
    {
        $sql = $this->createQueryBuilder('m')
            ->where('lower(m.name) LIKE :q')
            ->setParameter(':q', mb_strtolower($q, 'UTF-8') . '%');

        if ($id) {
            $sql->andWhere('m.department = :department')
                ->setParameter(':department', $id);
        }
            $sql = $sql->getQuery();

        return $sql->getResult();
    }
}
