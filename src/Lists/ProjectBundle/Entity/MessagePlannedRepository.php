<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Types\Type;

/**
 * MessagePlannedRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MessagePlannedRepository extends EntityRepository
{
    /**
     * get All messages
     *
     * @param int[]   $userIds
     * @param string  $startTimestamp
     * @param string  $endTimestamp
     * @param mixed[] $filters
     *
     * @return mixed[]
     */
    public function getAllMessages($userIds, $startTimestamp, $endTimestamp, $filters = array())
    {
        /** @var QueryBuilder $sql */
        $sql = $this->createQueryBuilder('m');
            $sql->select('MAX(m.id)', 'm.eventDatetime');
//            ->addSelect('hmv.handlingId as handlingId')
//            ->addSelect('hmv.createdate as createdate')
//            ->addSelect('hmv.typeName as typeName')
//            ->addSelect('hmv.typeSlug as typeSlug')
//            ->addSelect('hmv.typeStayactiontime as typeStayactiontime')
//            ->addSelect("hmv.userFullName as userFullName")
//            ->addSelect("hmv.userId as userId")
//            ->addSelect("o.shortname as organizationName")
//            ->addSelect("s.name as status")
//            ->addSelect("hmv.additionalType as additionalType");
            //->addSelect("hmv.nextCreatedate as nextCreatedate");
//        $sql
//            ->innerJoin('hmv.handling', 'h')
//            ->innerJoin('h.status', 's')
//            ->innerJoin('h.organization', 'o');


        // Time range
        $sql
            ->where('m.eventDatetime >= :startTimestamp')
            ->andWhere('m.eventDatetime <= :endTimestamp');

        // With colors
        /*$sql
            ->addSelect('select_next_handling_message_date(hm.id, hm.handling_id) as nextCreatedate');*/

//        $onlyLastMessages = true;

//        if (isset($filters['eventType'])) {
//            if ($filters['eventType'] == CalendarService::EVENT_TYPE_CHOICE_ALL) {
//                $onlyLastMessages = false;
//            }
//        }

        if (isset($filters['userIds']) && $filters['userIds']) {
            $userIds = explode(',', $filters['userIds']);
        }

        // Set handling future messages
//        if ($onlyLastMessages) {
//            $sql
//                ->andWhere('hmv.additionalType = :additionalType')
//                ->setParameter(':additionalType', HandlingMessage::ADDITIONAL_TYPE_FUTURE_MESSAGE);
//
//            $sql
//                ->andWhere(
//                    'hmv.id =
//                        (
//                            SELECT
//                                MAX(hm1.id)
//                            FROM
//                                ListsHandlingBundle:HandlingMessage hm1
//                            WHERE
//                                hm1.handling_id = hmv.handlingId
//                            AND 
//                                hm1.createdate = 
//                                (
//                                    SELECT
//                                        MAX(hm2.createdate)
//                                    FROM
//                                        ListsHandlingBundle:HandlingMessage hm2
//                                    WHERE
//                                        hm2.handling_id = hmv.handlingId
//                                )
//                        )'
//                );
//        }

//        $dateTimeFrom = new \DateTime();
//        $dateTimeTo = new \DateTime();

        $sql
            ->setParameter(':startTimestamp', new \DateTime(date('Y-m-d H:i:s', $startTimestamp)), Type::DATETIME)
            ->setParameter(':endTimestamp', new \DateTime(date('Y-m-d H:i:s', $endTimestamp)), Type::DATETIME);

//        if (isset($filters['type'])) {
//            $sql
//                ->andWhere('hmv.typeName in (:types)')
//                ->setParameter(':types', explode(',', $filters['type']));
//        }
        if ($userIds && sizeof($userIds)) {
            $sql
                ->innerJoin('m.user', 'u')
                ->andWhere('u.id in (:userIds)')
                ->setParameter(':userIds', $userIds);
        }
        $sql->innerJoin('m.project', 'p');
        $sql->orderBy('mp.id');
        $sql->orderBy('m.eventDatetime', 'DESC');

        return $sql
            ->getQuery()
            ->getResult();
    }
}
