<?php

namespace SD\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Lists\HandlingBundle\Entity\HandlingMessage;

class SalesController extends Controller
{
	protected $baseRoutePrefix = 'sales';
	protected $baseTemplate = 'Sales';

    public function indexAction()
    {
        return $this->render('SDCalendarBundle:' . $this->baseTemplate . ':index.html.twig');
    }

    public function handlingMessageAction()
    {
        $user = $this->getUser();

        $userIds = array($user->getId());

		$events = $this->getEventsByUserIds($userIds);

        return $this->render('SDCalendarBundle::base.html.twig', array(
            'events' => $events
        ));
    }

	/**
	 * Returns events depending on userIds and userRoles
	 *
	 * @param int[] $userIds
	 *
	 * @return mixed[]
	 */
	public function getEventsByUserIds($userIds)
	{
		$events = array();

		$handlingMessages = $this->getDoctrine()->getRepository('ListsHandlingBundle:HandlingMessage')
			->getFutureMessages($userIds);

		foreach ($handlingMessages as $handlingMessage)
		{
			$events[] = array(
				'title' => $this->getEventTitle($handlingMessage),
				'start' => $this->getEventStart($handlingMessage)->format('Y-m-d H:i:s'),
				'url' => $this->getEventUrl($handlingMessage),
				'className' => $this->getEventCssClass($handlingMessage)
			);
		}

		return $events;
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

		return (string) $handlingMessage->getType() . ' (' . $start->format('H:i').')';
	}

	/**
	 * Return specific event start time
	 *
	 * @param HandlingMessage $handlingMessage
	 *
	 * @return string
	 */
	public function getEventStart($handlingMessage)
	{
		return $handlingMessage->getCreatedate();
	}

	/**
	 * Return specific event url
	 *
	 * @param HandlingMessage $handlingMessage
	 *
	 * @return string
	 */
	public function getEventUrl($handlingMessage)
	{
		return $this->generateUrl('lists_' .$this->baseRoutePrefix . '_handling_show', array(
			'id' => $handlingMessage->getHandlingId()
		));
	}

	/**
	 * Return specific event css class name
	 *
	 * @param HandlingMessage $handlingMessage
	 *
	 * @return string
	 */
	public function getEventCssClass($handlingMessage)
	{
		return $handlingMessage->getType() ? 'calendar-event-' . $handlingMessage->getType()->getSlug() : '';
	}


}
