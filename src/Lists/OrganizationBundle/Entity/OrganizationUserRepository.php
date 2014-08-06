<?php

namespace Lists\OrganizationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * OrganizationUserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrganizationUserRepository extends EntityRepository
{
     /**
    * @param integer $organizationId
    *
    * @return Query
    */
    public function getOrganizationUsersQuery($organizationId)
    {
        return $this->createQueryBuilder('ou')
                ->select('u.firstName')
                ->addSelect('u.lastName')
                ->addSelect('u.email')
                ->addSelect('ou.id')
                ->addSelect('s.mobilephone')
                ->addSelect('l.name')
                ->addSelect('l.lukey')
                ->addSelect('ou.userId')
                ->innerJoin('ou.organization', 'o')
                ->leftJoin('ou.role', 'l')
                ->innerJoin('ou.user', 'u')
                ->innerJoin('u.stuff', 's')
                ->where('o.id = :organizationId')
                ->setParameter(':organizationId', $organizationId);
    }
}
