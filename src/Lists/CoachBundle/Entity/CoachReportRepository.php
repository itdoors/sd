<?php

namespace Lists\CoachBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CoachReportRepository
 */
class CoachReportRepository extends EntityRepository
{
    /**
     * getAll
     * 
     * @return mixed[]
     */
    public function getAll()
    {
        $sql = $this->createQueryBuilder('c');
        $sqlCount = $this->createQueryBuilder('c');

        $sqlCount->select('COUNT(c.id) as reportscount');

        $query = array (
            'entity' => $sql->getQuery(),
            'count' => $sqlCount->getQuery()->getSingleScalarResult()
        );

        return $query;
    }
}
