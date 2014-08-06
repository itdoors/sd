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
     * Renders tasks
     *
     * @return string
     */
    public function taskAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tasksDone = $em->getRepository('SDCalendarBundle:Task')->
            getPersonalTaskDone($this->getUser()->getId(), 'personal');
        $tasksOpen = $em->getRepository('SDCalendarBundle:Task')->
            getFivePersonalTaskOpen($this->getUser()->getId(), 'personal');

        return $this->render('SDCalendarBundle:Task:tasks.html.twig', array(
            'tasksDone' => $tasksDone,
            'tasksOpen' => $tasksOpen
        ));
    }

    /**
     * Renders done tasks
     *
     * @return string
     */
    public function tasksDoneAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tasksDone = $em->getRepository('SDCalendarBundle:Task')->
            getPersonalTaskDone($this->getUser()->getId(), 'personal');

        return $this->render('SDCalendarBundle:Task:tasksDone.html.twig', array(
            'tasksDone' => $tasksDone
        ));
    }

    /**
     * Update task. Make it done/viewed
     *
     * @param Request $request
     *
     * @return Response
     */
    public function setDoneTaskAction(Request $request)
    {
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('SDCalendarBundle:Task')->find($id);
        $task->setIsDone(true);
        $em->persist($task);
        $em->flush();
        $return = array();
        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * Renders modal inner html for one task
     *
     * @param Request $request
     *
     * @return Response
     */
    public function oneTaskAjaxAction(Request $request)
    {
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('SDCalendarBundle:Task')->find($id);

        $return = array();
        $return['html'] = $this->renderView('SDCalendarBundle:Task:taskModal.html.twig', array(
            'task' => $task
        ));

        return new Response(json_encode($return));
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
        $decision = $em->getRepository('ListsArticleBundle:Article')
                ->getDecisionForCalendar($this->getUser()->getId());
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
        $task = $em->getRepository('SDCalendarBundle:Task')->getPersonalTask($this->getUser()->getId(), 'personal');
        foreach ($task as $val) {
            $events[] = array(
                'hover_title' => '',
                'modal' => 'on',
                'data_id' => $val['id'],
                'title' => $val['title']. ' ('. $val['startDateTime']->format('H:i'). ')',
                'start' => $val['startDateTime']->format('Y-m-d H:i:s'),
                'end' => $val['stopDateTime']->format('Y-m-d H:i:s'),
                'allDay' => false,
                'url' => '#create_task'
                );
        }

        return $events;
    }
}
