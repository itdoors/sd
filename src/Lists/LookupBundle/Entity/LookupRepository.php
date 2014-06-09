<?php

namespace Lists\LookupBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * LookupRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LookupRepository extends EntityRepository
{
    const KEY__SCOPE = 'scope';
    const KEY__DOGOVOR = 'dogovor';
    const KEY__ORGANIZATION_SIGN_COMPETITOR = 'organization_sign_competitor';

    /**
     * Returns choices for scope
     *
     * @return Query
     */
    public function getOnlyScopeQuery()
    {
        return $this->getLookupByLukeyQuery(self::KEY__SCOPE);
    }

    /**
     * Returns choices for dogovor_type
     *
     * @return Query
     */
    public function getOnlyDogovorTypeQuery()
    {
        return $this->getLookupByLukeyQuery(self::KEY__DOGOVOR);
    }

    /**
     * Returns choices for competitor
     *
     * @return QueryBuilder
     */
    public function getOnlyCompetitorQuery()
    {
        return $this->getLookupByLukeyQuery(self::KEY__ORGANIZATION_SIGN_COMPETITOR);
    }

    /**
     * Get lookup by lukey
     *
     * @param string $lukey
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getLookupByLukeyQuery($lukey)
    {
        $query = $this->createQueryBuilder('l')
            ->where('l.lukey = :lukey')
            ->setParameter(':lukey', $lukey);

        return $query;
    }

    /**
     * Returns first id of collection
     *
     * @param string $lukey
     *
     * @return int|null
     */
    public function getFirstIdByLukey($lukey)
    {
        /** @var Lookup[]|Collection $query */
        $query = $this->getLookupByLukeyQuery($lukey)
            ->getQuery()->getResult();

        if (sizeof($query) && isset($query[0]) && $query[0] instanceof Lookup) {
            return $query[0]->getId();
        }

        return null;
    }
}
