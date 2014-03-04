<?php

namespace SD\CalendarBundle\Controller;

use Lists\HandlingBundle\Entity\HandlingMessageRepository;
use SD\CommonBundle\Controller\BaseFilterController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Lists\HandlingBundle\Entity\HandlingMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SalesController
 */
class SalesController extends BaseFilterController
{
	protected $baseRoutePrefix = 'sales';
	protected $baseTemplate = 'Sales';

    /**
     * Renders template holder for calendar
     */
    public function indexAction()
    {
        return $this->render('SDCalendarBundle:' . $this->baseTemplate . ':index.html.twig');
    }

    /**
     * Returms base calendar template for handlingMessage for Sales
     */
    public function handlingMessageAction(Request $request)
    {
        $startTimestamp = $request->query->get('start');
        $endTimestamp = $request->query->get('end');

        $user = $this->getUser();

        $userIds = array($user->getId());

		$events = $this->getEventsByUserIds($userIds, $startTimestamp, $endTimestamp);

        return new Response(json_encode($events));
    }

	/**
	 * Returns events depending on userIds and userRoles
	 *
	 * @param int[] $userIds
     * @param string $startTimestamp
     * @param string $endTimestamp
	 *
	 * @return mixed[]
	 */
	public function getEventsByUserIds($userIds, $startTimestamp, $endTimestamp)
	{
		$events = array();

        /** @var HandlingMessageRepository $handlingMessagesRepository */
        $handlingMessagesRepository = $this->get('lists_handling.message.repository');

        $filters = $this->getFilters($this->container->getParameter('ajax.filter.namespace.dashboard.calendar'));

        $handlingMessages = $handlingMessagesRepository
            ->getAllMessages($userIds, $startTimestamp, $endTimestamp, $filters);

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

		return (string) $handlingMessage['typeName'] . ' (' . $start->format('H:i').')';
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
		return $handlingMessage['createdate'];
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
			'id' => $handlingMessage['handlingId']
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
		$cssClass = $handlingMessage['typeName'] ? 'calendar-event-' . $handlingMessage['typeSlug'] : '';

        if ($handlingMessage['createdate'] < new \DateTime())
        {
            $cssClass .= ' sd-event-prev';
        }
        else
        {
            $cssClass .= ' sd-event-next';
        }

        return $cssClass;
	}


}
