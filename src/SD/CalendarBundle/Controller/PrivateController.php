<?php

namespace SD\CalendarBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PrivateController
 */
class PrivateController extends SalesController
{
    protected $baseRoutePrefix = 'private';
    protected $baseTemplate = 'Private';

    /**
     * Renders template holder for calendar
     *
     * @return string
     */
    public function indexAction()
    {
        return $this->render('SDCalendarBundle::base.html.twig', array(
            'url' => $this->get('router')->generate('sd_calendar_private_handling_message')
        ));
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

        /** get handling */
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
                ->getAllMessages($userIds, $startTimestamp, $endTimestamp, $filters);

            foreach ($handlingMessages as $handlingMessage) {
                $events[] = array(
                    'hover_title' => $this->getEventHoverTitle($handlingMessage),
                    'title' => $this->getEventTitle($handlingMessage),
                    'start' => $this->getEventStart($handlingMessage)->format('Y-m-d H:i:s'),
                    'url' => $this->getEventUrl($handlingMessage),
                    'className' => $this->getEventCssClass($handlingMessage),
//                    'allDay' => false,s
                );
            }
        }

        /** get article */
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

        /** get task */
        $task = $em->getRepository('SDCalendarBundle:Task')->findAll();
        foreach ($task as $val) {
            $events[] = array(
                'hover_title' => '',
                'modal' => 'on',
                'data_id' => 'la'.$val->getId(),
                'title' => $val->getTitle(). ' ('. $val->getStartDateTime()->format('H:i'). ')',
                'start' => $val->getStartDateTime()->format('Y-m-d H:i:s'),
                'end' => $val->getStopDateTime()->format('Y-m-d H:i:s'),
                'allDay' => false
//                'url' => $this->generateUrl('list_article_vote_decision_show', array(
//                            'id' => $val->getId()
//                    )
//                )
                );
        }
        return $events;
    }
}
