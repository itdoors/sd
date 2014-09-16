<?php

namespace SD\TaskBundle\Controller;

use SD\TaskBundle\Classes\TaskAccessFactory;
use SD\TaskBundle\Entity\Comment;
use SD\TaskBundle\Entity\TaskEndDate;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TaskController
 */
class TaskController extends Controller
{
    /**
     * Renders the index page with count of tasks and its list
     *
     * @return Response
     */
    public function indexAction ()
    {
        $user = $this->getUser();

        $filterArray = array (
            'user' => $user
        );

        $info = $this->getTasksInfoForTable($filterArray);

        $tasksUserRoleRepo = $this->getDoctrine()
            ->getRepository('SDTaskBundle:TaskUserRole');

        $countTasks = $tasksUserRoleRepo->countTasksByRoleAndUser($user->getId(), 'performer');
        $countTasks += $tasksUserRoleRepo->countTasksByRoleAndUser($user->getId(), 'controller');
        $info['countTasks'] = $countTasks;

        return $this->render('SDTaskBundle:Task:index.html.twig', $info);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function taskListAction(Request $request)
    {
        $filter = $request->request->get('filter');
        $user = $this->getUser();

        $filterArray = array (
            'user' => $user
        );

        if ($filter) {
            if (count($filter['role'])) {
                foreach ($filter['role'] as $filterRole) {
                    $role = $this->getDoctrine()
                        ->getRepository('SDTaskBundle:Role')
                        ->findOneBy(array (
                            'name' => $filterRole,
                            'model' => 'task'
                        ));
                    $filterArray['role'][] = $role;
                }
            }
        }

        $info = $this->getTasksInfoForTable($filterArray);

/*        $return = array();
        $return['html'] =
        $return['success'] = 1;*/

        return new Response($this->renderView('SDTaskBundle:Task:taskList.html.twig', $info));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function taskViewAction(Request $request)
    {
        $id = $request->request->get('id');

        $taskUserRole = $this->getDoctrine()
            ->getRepository('SDTaskBundle:TaskUserRole')
            ->find($id);

        $info = $this->getTaskUserRoleInfo($id);

        $commentRepository = $this->getDoctrine()
            ->getRepository('SDTaskBundle:Comment');

        $idTask = $taskUserRole->getTask()->getId();

        $comments = $commentRepository->findBy(array (
            'model' => 'Task',
            'modelId' => $idTask
        ), array (
            'createDatetime' => 'DESC'
        ));

        $info['comments'] = $comments;

        $info['taskUserRole'] = $taskUserRole;

        $return = array();
        $return['html'] = $this->renderView('SDTaskBundle:Task:taskView.html.twig', $info);
        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * @return Response
     */
    public function taskTableDashboardAction ()
    {
        $user = $this->getUser();

        $filterArray = array (
            'user' => $user
        );

        $info = $this->getTasksInfoForTable($filterArray);

        return $this->render('SDTaskBundle:Dashboard:tableTasks.html.twig', $info);
    }

    /**
     * @param array $filterArray
     *
     * @return array
     */
    private function getTasksInfoForTable($filterArray)
    {
        $tasksUserRoleRepo = $this->getDoctrine()
            ->getRepository('SDTaskBundle:TaskUserRole');

        $tasksUserRole = $tasksUserRoleRepo->getEntitiesListByFilter($filterArray);

        $allTaskRoles = $this->getDoctrine()
            ->getRepository('SDTaskBundle:Role')
            ->findBy(array (
                'model' => 'task'
            ));

        return array(
            'tasksUserRole' => $tasksUserRole,
            'allTaskRoles' => $allTaskRoles
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function renderDashboardTaskRowsAjaxAction(Request $request)
    {
        $filter = $request->request->get('filter');
        $user = $this->getUser();

        $filterArray = array (
            'user' => $user
        );

        if ($filter) {
            if (count($filter['role'])) {
                foreach ($filter['role'] as $filterRole) {
                    $role = $this->getDoctrine()
                        ->getRepository('SDTaskBundle:Role')
                        ->findOneBy(array (
                            'name' => $filterRole,
                            'model' => 'task'
                        ));
                    $filterArray['role'][] = $role;
                }
            }
        }

        $info = $this->getTasksInfoForTable($filterArray);

        $return = array();
        $return['html'] = $this->renderView('SDTaskBundle:Dashboard:tableTasksRows.html.twig', $info);
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
    public function taskModalAction (Request $request)
    {
        $id = $request->request->get('id');
        $return = array ();

        $info = $this->getTaskUserRoleInfo($id);

        $return['success'] = 1;
        $return['html'] = $this->renderView('SDTaskBundle:Task:taskModal.html.twig', $info);

        return new Response(json_encode($return));
    }
    /**
     * @param int $id
     *
     * @return array
     */
    protected function getTaskUserRoleInfo ($id)
    {
        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);
        $userId = $this->getUser()->getId();

        $roleRepository = $this->getDoctrine()
            ->getRepository('SDTaskBundle:Role');

        $performerRole = $roleRepository
            ->findOneBy(array (
            'name' => 'performer',
            'model' => 'task'
        ));

        $controllerRole = $roleRepository
            ->findOneBy(array (
            'name' => 'controller',
            'model' => 'task'
        ));

        $authorRole = $roleRepository
            ->findOneBy(array (
            'name' => 'author',
            'model' => 'task'
        ));

        $matcherRole = $roleRepository
            ->findOneBy(array(
                'name' => 'matcher',
                'model' => 'task'
            ));

        $taskUserRoleController = $em->getRepository('SDTaskBundle:TaskUserRole')->findBy(
            array (
                'task' => $taskUserRole->getTask(),
                'role' => $controllerRole
            )
        );

        $taskUserRolePerformer = $em->getRepository('SDTaskBundle:TaskUserRole')->findBy(
            array (
                'task' => $taskUserRole->getTask(),
                'role' => $performerRole
            )
        );

        $taskUserRoleAuthor = $em->getRepository('SDTaskBundle:TaskUserRole')->findBy(
            array (
                'task' => $taskUserRole->getTask(),
                'role' => $authorRole
            )
        );

        $taskUserRoleMatcher = $em->getRepository('SDTaskBundle:TaskUserRole')->findBy(
            array (
                'task' => $taskUserRole->getTask(),
                'role' => $matcherRole
            )
        );


        $comment = $this->getLastTaskComment($taskUserRole->getTask()->getId());

        $currentRole = $taskUserRole->getRole();
        $currentStage = $taskUserRole->getTask()->getStage();
        $currentIsViewed = $taskUserRole->getIsViewed();


        $access = TaskAccessFactory::createAccess($currentRole, $currentStage, $currentIsViewed);

        $info = array (
            'taskUserRole' => $taskUserRole,
            'taskUserRoleController' => $taskUserRoleController,
            'taskUserRolePerformer' => $taskUserRolePerformer,
            'taskUserRoleAuthor' => $taskUserRoleAuthor,
            'taskUserRoleMatcher' => $taskUserRoleMatcher,
            'userId' => $userId,
            'comment' => $comment,
            'access' => $access
        );

        return $info;
    }
    /**
     * @param int $idTask
     *
     * @return Comment
     */
    protected function getLastTaskComment ($idTask)
    {
        $commentRepository = $this->getDoctrine()
            ->getRepository('SDTaskBundle:Comment');

        $lastComment = $commentRepository->findOneBy(array (
            'model' => 'Task',
            //'user' => $user,
            'modelId' => $idTask
            ), array (
            'createDatetime' => 'DESC'
        ));

        return $lastComment;
    }
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function setIsViewedTaskAction (Request $request)
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
    /**
     * @param int  $id
     * @param bool $forceCheck
     */
    private function checkIfCanPerform ($id, $forceCheck = false)
    {

        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);

        $roleRepository = $this->getDoctrine()
            ->getRepository('SDTaskBundle:Role');

        $performerRole = $roleRepository
            ->findOneBy(array (
            'name' => 'performer',
            'model' => 'task'
        ));

        $task = $taskUserRole->getTask();
        $tasksUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->findBy(array (
            'task' => $task,
            'role' => $performerRole
        ));
        if ($taskUserRole->getRole()->getName() != 'controller' || $forceCheck) {
            $performing = true;
            foreach ($tasksUserRole as $taskPerforming) {
                if ($taskPerforming->getIsViewed() == false) {
                    $performing = false;
                    break;
                }
            }
            $currentStage = $task->getStage();
            if ($currentStage == 'created' || $currentStage == 'performing') {
                if ($performing) {
                    $stagePerforming = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array (
                        'name' => 'performing',
                        'model' => 'task'
                    ));
                    $task->setStage($stagePerforming);
                    $em->persist($task);
                } else {
                    $stageCreated = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array (
                        'name' => 'created',
                        'model' => 'task'
                    ));
                    $task->setStage($stageCreated);
                    $em->persist($task);
                }
                $em->flush();
            }
        }
    }
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function taskStageUpdateAction (Request $request)
    {
        $id = $request->request->get('id');
        $stage = $request->request->get('stage');
        $commentValue = $request->request->get('comment');

        if ($stage == 'done' || $stage == 'undone' || $stage == 'closed' || $stage == 'checking') {
            $this->closeDateRequest($id);
        }
        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);

        $task = $taskUserRole->getTask();
        $stageName = $stage;
        $stage = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array(
            'name' => $stage,
            'model' => 'task'
        ));
        $translator = $this->get('translator');

        $comment = $translator->trans('Changed the task stage', array(), 'SDTaskBundle');
        $stageTrans = $translator->trans($stageName, array(), 'SDTaskBundle');
        $text = $comment.' :'.$stageTrans;
        if ($commentValue) {
            $text .= '<br>
            '.$translator->trans('Comment', array(), 'SDTaskBundle').' :'.$commentValue;
        }
        $this->insertComment($id, $text);

        $task->setStage($stage);
        $em->persist($task);
        $em->flush();

        $return['success'] = 1;

        return new Response(json_encode($return));
    }
    /**
     * @param int $id
     */
    private function closeDateRequest($id)
    {
        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);
        $stageRequest = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array (
            'name' => 'request',
            'model' => 'task_end_date'
        ));
        $dateRequest = $em->getRepository('SDTaskBundle:TaskEndDate')->findOneBy(array (
            'task' => $taskUserRole->getTask(),
            'stage' => $stageRequest,
        ));

        $stageDate = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array (
            'name' => 'rejected',
            'model' => 'task_end_date',
        ));

        if ($dateRequest) {
            $dateRequest->setStage($stageDate);
            $em->persist($dateRequest);
            $em->flush();
        }
    }
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function taskChangeDateRequestAction (Request $request)
    {
        $id = $request->request->get('id');
        $value = $request->request->get('value');
        $type = $request->request->get('type');
        $commentValue = $request->request->get('comment');

        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);

        $task = $taskUserRole->getTask();

        $stageRequest = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array (
            'name' => 'request',
            'model' => 'task_end_date'
        ));

        $dateRequest = $em->getRepository('SDTaskBundle:TaskEndDate')->findBy(array (
            'task' => $task,
            'stage' => $stageRequest,
        ));

        if (sizeof($dateRequest)) {
            $return['success'] = 0;

            return new Response(json_encode($return));
        }

        $stageDate = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array (
            'name' => 'accepted',
            'model' => 'task_end_date',
        ));

        $date = $em->getRepository('SDTaskBundle:TaskEndDate')->findOneBy(array (
            'task' => $task,
            'stage' => $stageDate,
            ), array (
            'id' => 'DESC'
        ));


        if ($type == 'hour') {
            $stringAddDate = 'PT' . $value . 'H';
        } elseif ($type == 'day') {
            $stringAddDate = 'P' . $value . 'D';
        } elseif ($type == 'week') {
            $stringAddDate = 'P' . $value . 'W';
        } elseif ($type == 'month') {
            $stringAddDate = 'P' . $value . 'M';
        }


        $newDate = $date->getEndDateTime()->add(new \DateInterval($stringAddDate));

        $newTaskEndDate = new TaskEndDate();
        $newTaskEndDate->setEndDateTime($newDate);
        $translator = $this->get('translator');

        if ($taskUserRole->getRole() == 'controller') {
            $newTaskEndDate->setStage($stageDate);
            $comment = $translator->trans('Changed the end date', array(), 'SDTaskBundle').
                ' :'.$newDate->format('d-m-Y H:i');
            if ($commentValue) {
                $comment .= '<br>
            '.$translator->trans('Comment', array(), 'SDTaskBundle').' :'.$commentValue;
            }
            $this->insertComment($id, $comment);
        } else {
            $newTaskEndDate->setStage($stageRequest);
            $stageDateRequest = $em->getRepository('SDTaskBundle:Stage')->findOneby(array (
                'name' => 'date request',
                'model' => 'task',
            ));
            $task->setStage($stageDateRequest);
            $comment = $translator->trans('Made the end date request', array(), 'SDTaskBundle').
                ' :'.$newDate->format('d-m-Y H:i');
            if ($commentValue) {
                $comment .= '<br>
            '.$translator->trans('Comment', array(), 'SDTaskBundle').' :'.$commentValue;
            }
            $this->insertComment($id, $comment);
        }
        $newTaskEndDate->setTask($task);
        $newTaskEndDate->setChangeDateTime(new \DateTime());

        $em->persist($newTaskEndDate);

        $em->persist($task);
        $em->flush();

        $return['success'] = 1;

        return new Response(json_encode($return));
    }
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function answerDateAction(Request $request)
    {
        $id = $request->request->get('id');
        $answer = $request->request->get('answer');
        $commentValue = $request->request->get('comment');

        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);

        $task = $taskUserRole->getTask();

        $stageRequest = $em->getRepository('SDTaskBundle:Stage')->findOneby(array (
            'name' => 'request',
            'model' => 'task_end_date'
        ));

        $taskEndDateRequested = $em->getRepository('SDTaskBundle:TaskEndDate')->findOneBy(array (
            'task' => $task,
            'stage' => $stageRequest
        ));
        $translator = $this->get('translator');
        if ($answer) {
            $answerStageName = 'accepted';
            $comment = $translator->trans('Accepted the date request', array(), 'SDTaskBundle');
            if ($commentValue) {
                $comment .= '<br>
            '.$translator->trans('Comment', array(), 'SDTaskBundle').' :'.$commentValue;
            }
            $this->insertComment($id, $comment);
        } else {
            $answerStageName = 'rejected';
            $comment = $translator->trans('Rejected the date request', array(), 'SDTaskBundle');
            if ($commentValue) {
                $comment .= '<br>
            '.$translator->trans('Comment', array(), 'SDTaskBundle').' :'.$commentValue;
            }
            $this->insertComment($id, $comment);
        }
        $answerStage = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array (
            'name' => $answerStageName,
            'model' => 'task_end_date'
        ));
        $taskEndDateRequested->setStage($answerStage);
        $em->persist($taskEndDateRequested);

        $stageCreated = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array (
            'name' => 'created',
            'model' => 'task'
        ));

        $task->setStage($stageCreated);
        $em->persist($task);

        $em->flush();

        $this->checkIfCanPerform($id, true);

        $return['success'] = 1;

        return new Response(json_encode($return));
    }
    /**
     * Insert Comment to action
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addCommentAction (Request $request)
    {
        $id = $request->request->get('id');
        $commentValue = $request->request->get('comment');

        $this->insertComment($id, $commentValue);
        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * @param int    $idTaskUserRole
     * @param string $commentValue
     */
    protected function insertComment($idTaskUserRole, $commentValue)
    {
        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($idTaskUserRole);

        $idTask = $taskUserRole->getTask()->getId();
        $user = $this->getUser();
        $comment = new Comment();

        $comment->setValue($commentValue);
        $comment->setCreateDatetime(new \DateTime());
        $comment->setModel('Task');
        $comment->setModelId($idTask);
        $comment->setUser($user);

        $em->persist($comment);
        $em->flush();
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function renderListOfFilesAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);

        $idTask = $taskUserRole->getTask()->getId();

        $info = $this->getFilesInfoFromTask($idTask);

        return $this->render('SDTaskBundle:Task:taskFileList.html.twig', $info);
    }

    /**
     * @param int $idTask
     *
     * @return mixed
     */
    protected function getFilesInfoFromTask($idTask)
    {

        $em = $this->getDoctrine()->getManager();

        $task = $em->getRepository('SDTaskBundle:Task')->find($idTask);

        $files = $em->getRepository('SDTaskBundle:TaskFile')->findBy(array(
            'task' => $task
        ), array(
            'createDate' => 'DESC'
        ));

        $info['files'] = $files;

        return $info;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function changeDateAction(Request $request)
    {
        $id = $request->request->get('id');
        $value = $request->request->get('value');
        $commentValue = $request->request->get('comment');

        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);

        $task = $taskUserRole->getTask();

        $stageRequest = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array (
            'name' => 'request',
            'model' => 'task_end_date'
        ));

        $dateRequest = $em->getRepository('SDTaskBundle:TaskEndDate')->findBy(array (
            'task' => $task,
            'stage' => $stageRequest,
        ));

        if (sizeof($dateRequest)) {
            $return['success'] = 0;

            return new Response(json_encode($return));
        }

        $stageDate = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array (
            'name' => 'accepted',
            'model' => 'task_end_date',
        ));

        $date = $em->getRepository('SDTaskBundle:TaskEndDate')->findOneBy(array (
            'task' => $task,
            'stage' => $stageDate,
        ), array (
            'id' => 'DESC'
        ));

        $newDate = new \DateTime($value);

        $newTaskEndDate = new TaskEndDate();
        $newTaskEndDate->setEndDateTime($newDate);
        $translator = $this->get('translator');
        // comment block //
        if ($taskUserRole->getRole() == 'controller') {
            $newTaskEndDate->setStage($stageDate); //set without request, coz CONTROLLER did it, behold)
            $comment = $translator->trans('Changed the end date', array(), 'SDTaskBundle').
                ' :'.$newDate->format('d-m-Y H:i');
            if ($commentValue) {
                $comment .= '<br>
            '.$translator->trans('Comment', array(), 'SDTaskBundle').' :'.$commentValue;
            }

            $this->insertComment($id, $comment);
        } else {
            $newTaskEndDate->setStage($stageRequest);
            //set with request, coz CONTROLLER have to take a decision about it
            $stageDateRequest = $em->getRepository('SDTaskBundle:Stage')->findOneby(array (
                'name' => 'date request',
                'model' => 'task',
            ));
            $task->setStage($stageDateRequest);
            $comment = $translator->trans('Made the end date request', array(), 'SDTaskBundle').
                ' :'.$newDate->format('d-m-Y H:i');
            if ($commentValue) {
                $comment .= '<br>
            '.$translator->trans('Comment', array(), 'SDTaskBundle').' :'.$commentValue;
            }

            $this->insertComment($id, $comment);
        }
        // end comment block //
        $newTaskEndDate->setTask($task);
        $newTaskEndDate->setChangeDateTime(new \DateTime());

        $em->persist($newTaskEndDate);

        $em->persist($task);
        $em->flush();

        $return['success'] = 1;

        return new Response(json_encode($return));
    }
}
