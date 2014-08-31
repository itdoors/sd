<?php

namespace SD\TaskBundle\Controller;

use SD\TaskBundle\Entity\TaskEndDate;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardTaskController extends Controller
{
    public function indexAction()
    {
        return $this->render('SDTaskBundle:Dashboard:index.html.twig');
    }



    public function taskTableAction(Request $request = null) {
        $user = $this->getUser();

        $filterArray = array(
            'user' => $user
        );

        if ($request != null) {
            $filter = $request->request->get('filter');
            if ($filter != 'all' && $filter) {
                $role = $this->getDoctrine()
                    ->getRepository('SDTaskBundle:Role')
                    ->findOneBy(array(
                        'name' => $filter,
                        'model' => 'task'
                    ));
                $filterArray['role'] = $role;
            }
        }


        $tasksUserRole = $this->getDoctrine()
            ->getRepository('SDTaskBundle:TaskUserRole')
            ->findBy(
                $filterArray,
                array(
                    'isViewed' => 'ASC',
                    'id' => 'DESC'
                )
            );

        if ($filter) {
            $return = array();
            $return['html'] = $this->renderView('SDTaskBundle:Task:tableTasks.html.twig',
                array(
                    'tasksUserRole' => $tasksUserRole
                )
            );
            $return['success'] = 1;
            return new Response(json_encode($return));
        }
        return $this->render('SDTaskBundle:Task:tableTasks.html.twig',
            array(
              'tasksUserRole' => $tasksUserRole
            )
        );

    }


    /**
     * Renders modal inner html for one task
     *
     * @param Request $request
     *
     * @return Response
     */
    public function taskModalAction(Request $request)
    {
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);
        $userId = $this->getUser()->getId();

        $roleRepository = $this->getDoctrine()
            ->getRepository('SDTaskBundle:Role');

        $performerRole = $roleRepository
            ->findOneBy(array(
                'name' => 'performer',
                'model' => 'task'
            ));

        $controllerRole  = $roleRepository
            ->findOneBy(array(
                'name' => 'controller',
                'model' => 'task'
            ));

        $authorRole  = $roleRepository
            ->findOneBy(array(
                'name' => 'author',
                'model' => 'task'
            ));

        $taskUserRoleController = $em->getRepository('SDTaskBundle:TaskUserRole')->findBy(
            array(
                'task' => $taskUserRole->getTask(),
                'role' => $controllerRole
            ));

        $taskUserRolePerformer = $em->getRepository('SDTaskBundle:TaskUserRole')->findBy(
            array(
                'task' => $taskUserRole->getTask(),
                'role' => $performerRole
            ));

        $taskUserRoleAuthor = $em->getRepository('SDTaskBundle:TaskUserRole')->findBy(
            array(
                'task' => $taskUserRole->getTask(),
                'role' => $authorRole
            ));

        $return = array();

        $return['success'] = 1;
        $return['html'] = $this->renderView('SDTaskBundle:Task:taskModal.html.twig', array(
            'taskUserRole' => $taskUserRole,
            'taskUserRoleController' => $taskUserRoleController,
            'taskUserRolePerformer' => $taskUserRolePerformer,
            'taskUserRoleAuthor' => $taskUserRoleAuthor,
            'userId' => $userId
        ));

        return new Response(json_encode($return));
    }


    public function setIsViewedTaskAction(Request $request)
    {
        $id = $request->request->get('id');

        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);
        $taskUserRole->setIsViewed(true);
        $em->persist($taskUserRole);
        $em->flush();

        //if all performers viewed, then task is performing
        $this->checkIfCanPerform($id);
        //end if performing


        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    private function checkIfCanPerform($id, $type = false) {

        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);

        $roleRepository = $this->getDoctrine()
            ->getRepository('SDTaskBundle:Role');

        $performerRole = $roleRepository
            ->findOneBy(array(
                'name' => 'performer',
                'model' => 'task'
            ));

        $task = $taskUserRole->getTask();
        $tasksUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->findBy(array(
            'task' => $task,
            'role'  => $performerRole
        ));
        if($taskUserRole->getRole()->getName() != 'controller' || $type) {
            $performing = true;
            foreach ($tasksUserRole as $taskPerforming) {
                if ($taskPerforming->getIsViewed() == false) {
                    $performing = false;
                    break;
                }
            }

            if ($performing) {
                $stagePerforming = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array(
                    'name' => 'performing',
                    'model'  => 'task'
                ));
                $task ->setStage($stagePerforming);
                $em->persist($task);
            } else {
                $stageCreated = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array(
                    'name' => 'created',
                    'model'  => 'task'
                ));
                $task ->setStage($stageCreated);
                $em->persist($task);
            }
        }
        $em->flush();
    }

    public function taskStageUpdateAction(Request $request) {
        $id = $request->request->get('id');
        $stage = $request->request->get('stage');

        if ($stage == 'done' || $stage == 'undone' || $stage == 'closed' || $stage == 'checking') {
            $this->closeDateRequest($id);
        }
        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);

        $task = $taskUserRole->getTask();

        $stage = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array(
            'name' => $stage,
            'model'  => 'task'
        ));

        $task ->setStage($stage);
        $em->persist($task);
        $em->flush();

        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    private function closeDateRequest($id) {
        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);
        $stageRequest = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array(
            'name' => 'request',
            'model' => 'task_end_date'
        ));
        $dateRequest= $em->getRepository('SDTaskBundle:TaskEndDate')->findOneBy(array(
            'task' => $taskUserRole->getTask(),
            'stage' => $stageRequest,
        ));

        $stageDate = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array(
            'name' => 'rejected',
            'model' => 'task_end_date',
        ));

        if ($dateRequest) {
            $dateRequest->setStage($stageDate);
            $em->persist($dateRequest);
            $em->flush;
        }


    }

    public function taskChangeDateRequestAction(Request $request) {
        $id = $request->request->get('id');
        $value = $request->request->get('value');
        $type = $request->request->get('type');

        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);

        $task = $taskUserRole->getTask();

        $stageRequest = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array(
            'name' => 'request',
            'model' => 'task_end_date'
        ));

        $dateRequest= $em->getRepository('SDTaskBundle:TaskEndDate')->findBy(array(
           'task' => $task,
           'stage' => $stageRequest,
        ));

        if (sizeof($dateRequest)) {
            $return['success'] = 0;

            return new Response(json_encode($return));
        }

        $stageDate = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array(
            'name' => 'accepted',
            'model' => 'task_end_date',
        ));

        $date = $em->getRepository('SDTaskBundle:TaskEndDate')->findOneBy(array(
            'task' => $task,
            'stage' => $stageDate,
        ), array(
            'id' => 'DESC'
        ));


        if ($type == 'hour') {
            $stringAddDate = 'PT'.$value.'H';
        } else if ($type == 'day') {
            $stringAddDate = 'P'.$value.'D';
        } else if ($type == 'week') {
            $stringAddDate = 'P'.$value.'W';
        } else if ($type == 'month') {
            $stringAddDate = 'P'.$value.'M';
        }


        $newDate = $date->getEndDateTime()->add(new \DateInterval($stringAddDate));

        $newTaskEndDate = new TaskEndDate();
        $newTaskEndDate->setEndDateTime($newDate);
        if ($taskUserRole->getRole() == 'controller') {
            $newTaskEndDate->setStage($stageDate);
        } else {
            $newTaskEndDate->setStage($stageRequest);
            $stageDateRequest = $em->getRepository('SDTaskBundle:Stage')->findOneby(array(
                'name' => 'date request',
                'model' => 'task',
            ));
            $task ->setStage($stageDateRequest);
        }
        $newTaskEndDate->setTask($task);
        $newTaskEndDate->setChangeDateTime(new \DateTime());

        $em->persist($newTaskEndDate);




        $em->persist($task);
        $em->flush();

        $return['success'] = 1;

        return new Response(json_encode($return));

    }

    public function answerDateAction (Request $request) {
        $id = $request->request->get('id');
        $answer = $request->request->get('answer');

        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);

        $task = $taskUserRole->getTask();

        $stageRequest = $em->getRepository('SDTaskBundle:Stage')->findOneby(array(
            'name' => 'request',
            'model' => 'task_end_date'
        ));

        $taskEndDateRequested = $em->getRepository('SDTaskBundle:TaskEndDate')->findOneBy(array(
            'task' => $task,
            'stage' => $stageRequest
        ));

        if ($answer) {
            $answerStageName = 'accepted';
        } else {
            $answerStageName = 'rejected';

        }
        $answerStage = $em->getRepository('SDTaskBundle:Stage')->findOneby(array(
            'name' => $answerStageName,
            'model' => 'task_end_date'
        ));
        $taskEndDateRequested->setStage($answerStage);
        $em->persist($taskEndDateRequested);
        $em->flush();

        $this->checkIfCanPerform($id, true);

        $return['success'] = 1;

        return new Response(json_encode($return));

    }
}
