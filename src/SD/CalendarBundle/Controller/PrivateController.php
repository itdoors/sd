<?php

namespace SD\CalendarBundle\Controller;

use SD\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Translator;

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
            'url' => $this->get('router')->generate('sd_calendar_private_message')
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
        $taskRepo = $em->getRepository('SDCalendarBundle:Task');
        $tasksDone = $taskRepo->
            getPersonalTaskDone($this->getUser()->getId(), 'personal');
        $tasksOpen = $taskRepo->
            getFivePersonalTaskOpen($this->getUser()->getId(), 'personal');
        $tasksCreated = $taskRepo->
            getFivePersonalTaskCreated($this->getUser()->getId(), 'personal');

        return $this->render('SDCalendarBundle:Task:tasks.html.twig', array(
            'tasksDone' => $tasksDone,
            'tasksOpen' => $tasksOpen,
            'tasksCreated' => $tasksCreated
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
        $userId = $this->getUser()->getId();
        $return = array();
        $return['html'] = $this->renderView('SDCalendarBundle:Task:taskModal.html.twig', array(
            'task' => $task,
            'userId' => $userId
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
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $events = array();
        /** @var Translator $translator */
        $translator = $this->container->get('translator');
        /** get handling */
        if ($user->hasRole('ROLE_SALES') || $this->getUser()->hasRole('ROLE_SALESADMIN')) {
            $session = $this->get('session');
            $managerForCalendar = $session->get('managerForCalendar', null);
            if ($managerForCalendar) {
                $userIds = array($managerForCalendar);
            }
        
            $projects = $em->getRepository('ListsProjectBundle:Project')->getLastMessages($userIds, $startTimestamp, $endTimestamp);

            foreach ($projects as $project) {
                $url = $this->generateUrl('lists_project_'.$project['project']->getDiscr().'_show', array(
                        'id' => $project['project']->getId()
                    ));
                $title = $project['nameType']. ' | '. $project['nameOrganization']. ' (' . $project['eventDatetime']->format('H:i').')';
                $cssClass = 'projectMessage';
                if ($project['eventDatetime'] < new \DateTime()) {
                    $cssClass .= ' sd-event-prev';
                    if ($project['project']->getIsClosed() && $project['project']->getDatetimeClosed() < $project['eventDatetime']) {
                        $cssClass .= ' sd-event-yellow';
                    } else {
                        $cssClass .= ' sd-event-red ';
                    }
                } else {
                    $cssClass .= ' sd-event-green';
                }
                $editable = true;
                if ($managerForCalendar) {
                    $editable = false;
                }
                $events[] = array(
                    'hover_title' => '',
                    'editable' => $editable,
                    'messageUrlUpdate' => $this->generateUrl('lists_project_message_update', array( 'id' => $project['messageId'])),
                    'title' => $title,
                    'start' => $project['eventDatetime']->format('Y-m-d H:i:s'),
                    'end' => $project['eventDatetime']->format('Y-m-d H:i:s'),
                    'url' => $url,
                    'className' => $cssClass,
                    'allDay' => false,
                );
            }
        }

        /** get article */
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
        /** @var User[] $users get birthdays */
        $users = $em->getRepository('SDUserBundle:User')
                ->getBirthdaysForCalendar($startTimestamp, $endTimestamp);
        foreach ($users as $user) {
            $fullName = $user->getFullname();
            $events[] = array(
                'hover_title' => '',
                'editable' => false,
                'title' => $translator->trans('BD', array(), 'SDDashboardBundle').' '. $fullName,
                'start' => date('Y').'-'.$user->getBirthday()->format('m-d'),
                'end' => date('Y').'-'.$user->getBirthday()->format('m-d'),
                'allDay' => true,
                'url' => $this->generateUrl('sd_user_show', array(
                    'id' => $user->getId()
                )),
                );
        }
        /** @var Holiday[] $holidays */
        $holidays = $em->getRepository('SDCalendarBundle:Holiday')
                ->getListForDate($startTimestamp, $endTimestamp);
        foreach ($holidays as $holiday) {
            $name = $holiday->getName();
            $events[] = array(
                'hover_title' => '',
                'title' => $name,
                'start' => date('Y').'-'.$holiday->getDate()->format('m').'-'.$holiday->getDate()->format('d'),
                'end' => date('Y').'-'.$holiday->getDate()->format('m').'-'.$holiday->getDate()->format('d'),
                'allDay' => true
                );
        }
        //  ProjectStateTender
        $gosTenders = $em->getRepository('ListsProjectBundle:ProjectStateTender')
                ->getListForDate($startTimestamp, $endTimestamp, $this->getUser());
        foreach ($gosTenders as $tender) {
            $name = $translator->trans('Tender', array(), 'ListsProjectBundle').' | '. $tender
                ->getProject()->getOrganization()->getCity();
            $events[] = array(
                'hover_title' => '',
                'title' => $name,
                'url' => $this->generateUrl('lists_project_state_tender_show', array(
                        'id' => $tender->getId()
                    )),
                'start' => $tender->getDatetimeDeadline()->format('Y-m-d H:i'),
                'end' => $tender->getDatetimeDeadline()->format('Y-m-d H:i')
                );
        }

        //tasks
        $taskUserRoles = $em->getRepository('SDTaskBundle:TaskUserRole')
            ->findBy(array(
                'user' => $user
            ));
        $stageAccepted = $em->getRepository('SDTaskBundle:Stage')
            ->findOneBy(array(
                'name' => 'accepted',
                'model' => 'task_end_date'
            ));
        $rolePerformerController = $em->getRepository('SDTaskBundle:Role')
            ->findBy(array(
                'name' => array('performer', 'controller'),
                'model' => 'task'
            ));
        //var_dump(count($taskUserRoles));die();
        foreach ($taskUserRoles as $taskUserRole) {
            $task = $taskUserRole->getTask();
            $stage = $task->getStage();
            $role = $taskUserRole->getRole();
            if ($stage == 'closed' || $stage == 'undone' || $stage == 'done') {
                continue;
            }
            if ($stage == 'matching' && ($role == 'performer' || $role='controller')) {
                continue;
            }
            if ($role == 'author') {
                $userTask = $taskUserRole->getUser();
                $otherTaskUserRoles = $em->getRepository('SDTaskBundle:TaskUserRole')
                    ->findBy(array(
                        'user' => $userTask,
                        'task' => $task,
                        'role' => $rolePerformerController
                    ));
                if ($otherTaskUserRoles) {
                    continue;
                }
            }
            $endDate = $em->getRepository('SDTaskBundle:TaskEndDate')
                ->findOneBy(array(
                    'task' => $task,
                    'stage' => $stageAccepted
                ));

            $events[] = array(
                'hover_title' => '',
                'title' => $taskUserRole->getTask()->getTitle(),
                'start' => $endDate->getEndDateTime()->format('Y-m-d H:i'),
                'end' => $endDate->getEndDateTime()->format('Y-m-d H:i'),
                'url' => $this->generateUrl('sd_task_homepage').'?id='.$taskUserRole->getId()
                //'allDay' => true
            );
        }

        return $events;
    }
}
