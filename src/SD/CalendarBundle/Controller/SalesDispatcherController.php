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

		$events = $this->getEventsByUserIds($teamUserIds, $startTimestamp, $endTimestamp);

        return new Response(json_encode($events));
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

		return (string)$handlingMessage['userFullName']
				. ' | '. (string) $handlingMessage['typeName']
				. ' (' . $start->format('H:i').')';
	}
}
