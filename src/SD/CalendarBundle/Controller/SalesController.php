<?php

namespace SD\CalendarBundle\Controller;

use Lists\HandlingBundle\Entity\HandlingMessageViewRepository;
use Lists\HandlingBundle\Services\HandlingMessageService;
use ITDoors\CommonBundle\Controller\BaseFilterController;
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
     *
     * @return string
     */
    public function indexAction()
    {
        return $this->render('SDCalendarBundle:' . $this->baseTemplate . ':index.html.twig');
    }

    /**
     * Returms base calendar template for handlingMessage for Sales
     *
     * @param Request $request
     *
     * @return string
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
     * @param int[]  $userIds
     * @param string $startTimestamp
     * @param string $endTimestamp
     *
     * @return mixed[]
     */
    public function getEventsByUserIds($userIds, $startTimestamp, $endTimestamp)
    {
        $events = array();

        if (
            $this->getUser()->hasRole('ROLE_SALES')
            ||
            $this->getUser()->hasRole('ROLE_SALESADMIN')
            ||
            $this->getUser()->hasRole('ROLE_SALEDISPATCHER')) {
            /** @var HandlingMessageViewRepository $handlingMessagesViewRepository */
            $handlingMessagesViewRepository = $this->get('lists_handling.message.view.repository');

            $filters = $this->getFilters($this->container->getParameter('ajax.filter.namespace.dashboard.calendar'));
            $handlingMessages = $handlingMessagesViewRepository
                ->getAllMessages($userIds, $startTimestamp, $endTimestamp, $filters['filter']);

            foreach ($handlingMessages as $handlingMessage) {
                $events[] = array(
                    'hover_title' => $this->getEventHoverTitle($handlingMessage),
                    'title' => $this->getEventTitle($handlingMessage),
                    'start' => $this->getEventStart($handlingMessage)->format('Y-m-d H:i:s'),
                    'url' => $this->getEventUrl($handlingMessage),
                    'className' => $this->getEventCssClass($handlingMessage)
                );
            }
        }
        $em = $this->getDoctrine()->getManager();
        $decision = $em->getRepository('ListsArticleBundle:Article')->getDecisionForCalendar($this->getUser()->getId());
        foreach ($decision as $val) {
            $events[] = array(
                'hover_title' => '',
                'title' => $val['title']. ' ('. $val['dateUnpublick']->format('H:i'). ')',
                'start' => $val['dateUnpublick']->format('Y-m-d H:i:s'),
                'url' => $this->generateUrl('list_article_vote_decision_show', array(
                            'id' => $val['id']
                        )),
                'className' => 'sd-event-next'
            );
        }

        return $events;
    }

    /**
     * Return specific event title
     *
     * @param Message $message
     *
     * @return string
     */
    public function getEventTitle($message)
    {
        $start = $this->getEventStart($message);

        return (string) $message->getType()
            . ' | '. $message->getProject()->getOrganization()
            . ' (' . $start->format('H:i').')';
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
        return '';
    }

    /**
     * Return specific event start time
     *
     * @param Message $message
     *
     * @return string
     */
    public function getEventStart($message)
    {
        return $message->getEventDatetime();
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
     * @param Message $message
     *
     * @return string
     */
    public function getEventCssClass($message)
    {
        $cssClass = $message->getType() ? 'calendar-event-' . $message->getType()->getSlug() : '';

        if ($message->getEventDatetime() < new \DateTime()) {
            $cssClass .= ' sd-event-prev';
        } else {
            $cssClass .= ' sd-event-next';
        }

        return $cssClass . ' ' . $this->getEventColorClassName($message);
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
        //return $handlingMessage['nextCreatedate'];
        return new \DateTime();
    }

    /**
     * Return addtional type of handling message
     *
     * @param Message $message
     *
     * @return string
     */
    public function getAdditionType($message)
    {
        return 'rf';
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
        if ($this->getAdditionType($handlingMessage) == HandlingMessage::ADDITIONAL_TYPE_FUTURE_MESSAGE) {
            return true;
        }

        return false;
    }

    /**
     * Return event color class name depending on handling type stay action
     *
     * @param Message $message
     *
     * @return string
     */
    public function getEventColorClassName($message)
    {
        // Old messages (not used now( )
        if (!$this->isFutureMessage($message)) {
            return 'sd-event-grey';
        }

//        $stayActiontime = $handlingMessage['typeStayactiontime'];
//
//        /** @var \DateTime $nextCreatedate */
//        $nextCreatedate = $this->getNextMessageCreatedate($handlingMessage);
//
//        /** @var \DateTime $creatdate */
//        $creatdate = $this->getEventStart($handlingMessage);
//
//        $now = new \DateTime();
//
//        // Future events
//
//        if ($creatdate > $now) {
//            return HandlingMessageService::$eventColors['green'];
//        }
//
//        $nextCreatedateU = $nextCreatedate ? $nextCreatedate->format('U') : $now->format('U');
//
//        $creatdateU = $creatdate->format('U');
//
//        $eventDateDiff = ($nextCreatedateU - $creatdateU) / 60;
//
//        if (($eventDateDiff - $stayActiontime) < 0) {
//            return HandlingMessageService::$eventColors['yellow'];
//        }
//
//        if (($eventDateDiff - $stayActiontime) > 0) {
//            return HandlingMessageService::$eventColors['red'];
//        }
//
//        return HandlingMessageService::$eventColors['red'];
    }
}
