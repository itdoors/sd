<?php

namespace SD\CalendarBundle\Controller;

use Lists\HandlingBundle\Entity\HandlingMessage;

class SalesDispatcherController extends SalesController
{
	protected $baseRoutePrefix = 'sales_dispatcher';
	protected $baseTemplate = 'SalesDispatcher';

	public function handlingMessageAction()
	{
		$user = $this->getUser();

		/** @var \Lists\TeamBundle\Entity\TeamRepository $teamRepository */
		$teamRepository = $this->get('lists_team.repository');

		/** @var \SD\UserBundle\Entity\User $user */
		$user = $this->getUser();

		$teamUserIds = $teamRepository->getMyTeamIdsByUser($user);

		$events = $this->getEventsByUserIds($teamUserIds);

		return $this->render('SDCalendarBundle::base.html.twig', array(
			'events' => $events
		));
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

		return (string)$handlingMessage->getUser()
				. ' | '. (string) $handlingMessage->getType()
				. ' (' . $start->format('H:i').')';
	}
}
