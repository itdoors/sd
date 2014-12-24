<?php

namespace Lists\IndividualBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * IndividualRepository
 */
class IndividualRepository extends EntityRepository
{
    /**
     * get coach action members
     *
     * @param string $searchText
     *
     * @return mixed[]
     */
    public function getMembers($searchText)
    {
        $members = $this->createQueryBuilder('i')
            ->select('DISTINCT i')
            ->join('i.actions', 'a')
            ->where('lower(i.firstName) LIKE :q OR lower(i.lastName) LIKE :q')
            ->setParameter(':q', '%' . $searchText . '%')
            ->getQuery()
            ->getResult();

        return $members;
    }
}
