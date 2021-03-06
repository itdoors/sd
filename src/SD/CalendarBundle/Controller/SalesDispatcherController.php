<?php

namespace SD\CalendarBundle\Controller;

use Lists\HandlingBundle\Entity\HandlingMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SalesAdminController
 */
class SalesDispatcherController extends SalesController
{
    protected $baseRoutePrefix = 'sales_dispatcher';
    protected $baseTemplate = 'SalesDispatcher';

    /**
     * Renders calendar for handlingMessage for dispatcher
     *
     * @param Request $request
     *
     * @return string
     */
    public function handlingMessageAction(Request $request)
    {
        $startTimestamp = $request->query->get('start');
        $endTimestamp = $request->query->get('end');

        /** @var \Lists\TeamBundle\Entity\TeamRepository $teamRepository */
        $teamRepository = $this->get('lists_team.repository');

        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $teamUserIds = $teamRepository->getMyTeamIdsByUser($user);

        $filters = $this->getFilters($this->container->getParameter('ajax.filter.namespace.dashboard.calendar'));

        $events = $this->getEventsByUserIds($teamUserIds, $startTimestamp, $endTimestamp, $filters);

        return new Response(json_encode($events));
    }

     /**
     * Return specific event title
     *
     * @param HandlingMessage $handlingMessage
     *
     * @return string
     */
    public function getEventHoverTitle($handlingMessage)
    {
        return (string) $handlingMessage['typeName']. ' | '. $handlingMessage['status'];
    }

    /**
     * Return specific event title
     *
     * @param HandlingMessage $handlingMessage
     *
     * @return string
     */
    public function getEventTitle($handlingMessage)
    {
        $start = $this->getEventStart($handlingMessage);

        $arrName = explode(' ', $handlingMessage['userFullName']);
        $strName = $arrName[0].(array_key_exists('1', $arrName)?
                ' '.mb_substr($arrName[1], 0, 1, 'UTF-8'):''
            );

        return $strName
            . ' | '. $handlingMessage['organizationName']
            . ' (' . $start->format('H:i').')';
    }
}
