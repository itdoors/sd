<?php

namespace Lists\ContactBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;

/**
 * ModelContactLevelRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ModelContactLevelRepository extends EntityRepository
{
    /**
     * @return ModelContactLevel[]|Collection
     */
    public function getLevels()
    {
        return $this->createQueryBuilder('mcl')
            ->orderBy('mcl.digit')
            ->getQuery()
            ->getResult();
    }
}
