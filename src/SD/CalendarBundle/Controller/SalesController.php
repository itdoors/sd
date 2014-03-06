<?php

namespace SD\CalendarBundle\Controller;

use Lists\HandlingBundle\Entity\HandlingMessageViewRepository;
use Lists\HandlingBundle\Services\HandlingMessageService;
use SD\CommonBundle\Controller\BaseFilterController;
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

        /** @var HandlingMessageViewRepository $handlingMessagesViewRepository */
        $handlingMessagesViewRepository = $this->get('lists_handling.message.view.repository');

        $filters = $this->getFilters($this->container->getParameter('ajax.filter.namespace.dashboard.calendar'));

        $handlingMessages = $handlingMessagesViewRepository
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

        return $cssClass . ' ' . $this->getEventColorClassName($handlingMessage);
	}

    /**
     * Return next message createdate
     *
     * @param HandlingMessage $handlingMessage
     *
     * @return string
     */
    public function getNextMessageCreatedate($handlingMessage)
    {
        return $handlingMessage['nextCreatedate'];
    }

    /**
     * Return addtional type of handling message
     *
     * @param HandlingMessage $handlingMessage
     *
     * @return string
     */
    public function getAdditionType($handlingMessage)
    {
        return $handlingMessage['additionalType'];
    }

    /**
     * Is future messge
     *
     * @param HandlingMessage $handlingMessage
     *
     * @return bool
     */
    public function isFutureMessage($handlingMessage)
    {
        if ($this->getAdditionType($handlingMessage) == HandlingMessage::ADDITIONAL_TYPE_FUTURE_MESSAGE)
        {
            return true;
        }

        return false;
    }

    /**
     * Return event color class name depending on handling type stay action
     *
     * @param HandlingMessage $handlingMessage
     *
     * @return string
     */
     public function getEventColorClassName($handlingMessage)
     {
         // Old messages (not used now( )
         if (!$this->isFutureMessage($handlingMessage))
         {
             return HandlingMessageService::$eventColors['grey'];
         }

         $stayActiontime = $handlingMessage['typeStayactiontime'];

         /** @var \DateTime $nextCreatedate */
         $nextCreatedate = $this->getNextMessageCreatedate($handlingMessage);

         /** @var \DateTime $creatdate */
         $creatdate = $this->getEventStart($handlingMessage);

         $now = new \DateTime();

         // Future events

         if ($creatdate > $now)
         {
             return HandlingMessageService::$eventColors['green'];
         }

         $nextCreatedateU = $nextCreatedate ? $nextCreatedate->format('U') : $now->format('U');

         $creatdateU = $creatdate->format('U');

         $eventDateDiff = ($nextCreatedateU - $creatdateU) / 60;

         if (($eventDateDiff - $stayActiontime) < 0)
         {
             return $nextCreatedate ?
                    HandlingMessageService::$eventColors['blue'] :
                    HandlingMessageService::$eventColors['green'];
         }

         if (($eventDateDiff - $stayActiontime) > 0)
         {
             return $nextCreatedate ?
                 HandlingMessageService::$eventColors['red'] :
                 HandlingMessageService::$eventColors['red'];
         }

         return HandlingMessageService::$eventColors['red'];
     }
}
