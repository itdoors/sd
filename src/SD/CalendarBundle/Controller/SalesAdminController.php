<?php

namespace SD\CalendarBundle\Controller;

class SalesAdminController extends SalesDispatcherController
{
	protected $baseRoutePrefix = 'sales_admin';
	protected $baseTemplate = 'SalesAdmin';

	public function handlingMessageAction()
	{
		$user = $this->getUser();

		$userIds = array($user->getId());

		$events = $this->getEventsByUserIds(null);

		return $this->render('SDCalendarBundle::base.html.twig', array(
			'events' => $events
		));
	}
}
