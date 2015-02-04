<?php

namespace SD\TaskBundle\Controller;

use SD\TaskBundle\Classes\ComparatorTaskAccess;
use SD\TaskBundle\Classes\TaskAccessFactory;
use SD\TaskBundle\Entity\Comment;
use SD\TaskBundle\Entity\TaskCommit;
use SD\TaskBundle\Entity\TaskEndDate;
use SD\TaskBundle\Entity\TaskUserRole;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TaskController
 */
class TaskController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction (Request $request)
    {
        $preloadIdTaskUserRole = $request->query->get('id');

        $user = $this->getUser();

        $filterArray = array (
            'user' => $user
        );

        $info = $this->getTasksInfoForTable($filterArray);

        $tasksUserRoleRepo = $this->getDoctrine()
            ->getRepository('SDTaskBundle:TaskUserRole');

        $countTasks = array();
        $filterArray['role'] = 'performer';
        $countTasks['performer'] = $tasksUserRoleRepo->getEntitiesListFilter1($filterArray, 'count');
        $filterArray['role'] = 'controller';
        $countTasks['controller'] = $tasksUserRoleRepo->getEntitiesListFilter1($filterArray, 'count');
        $filterArray['role'] = 'matcher';
        $countTasks['matcher'] = $tasksUserRoleRepo->getEntitiesListFilter1($filterArray, 'count');
        $filterArray['role'] = 'author';
        $countTasks['author'] = $tasksUserRoleRepo->getEntitiesListFilter1($filterArray, 'count');
        $filterArray['role'] = 'viewer';
        $countTasks['viewer'] = $tasksUserRoleRepo->getEntitiesListFilter1($filterArray, 'count');
        $info['countTasks'] = $countTasks;
        $info['preloadIdTaskUserRole'] = $preloadIdTaskUserRole;

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

        $filterArray['showClosed'] = (bool) $request->request->get('showClosed');

        if ($filter) {
            if (count($filter['role'])) {
                $filterArray['role'] = $filter['role'][0];


/*                foreach ($filter['role'] as $filterRole) {
                    $role = $this->getDoctrine()
                        ->getRepository('SDTaskBundle:Role')
                        ->findOneBy(array (
                            'name' => $filterRole,
                            'model' => 'task'
                        ));
                    $filterArray['role'][] = $filterRole;
                }*/
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
    public function listItemAction(Request $request)
    {
        $id = $request->request->get('id');

        $user = $this->getUser();
        $filterArray = array (
            'user' => $user
        );

        $tasksUserRoleRepo = $this->getDoctrine()
            ->getRepository('SDTaskBundle:TaskUserRole');

        $taskUserRole = $tasksUserRoleRepo->find($id);

        $info['taskUserRole'] = $taskUserRole;

        $return['html'] = $this->renderView('SDTaskBundle:Task:taskListItem.html.twig', $info);

        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function taskViewAction(Request $request, $id = null)
    {
        $json = false;
        if (!$id) {
            $json = true;
            $id = $request->request->get('id');
        }

        $taskUserRole = $this->getDoctrine()
            ->getRepository('SDTaskBundle:TaskUserRole')
            ->find($id);
        $user = $this->getUser();

        $updateTaskItemsId = false;
        if ($taskUserRole->getUser() != $user) {

            return $this->render('SDTaskBundle:Task:noTaskAccess.html.twig', array());
        } else {
            if (!$taskUserRole->getIsViewed() || $taskUserRole->getIsUpdated()) {
                $this->setIsViewedTaskById($id);
                $updateTaskItemsId = $taskUserRole->getTask()->getId();
            }

        }
        $info = $this->getTaskUserRoleInfo($id);
        $idTask = $taskUserRole->getTask()->getId();

        $commentRepository = $this->getDoctrine()
            ->getRepository('SDTaskBundle:Comment');


        $comments = $commentRepository->findBy(array (
            'model' => 'Task',
            'modelId' => $idTask
        ), array (
            'createDatetime' => 'DESC'
        ));

        $info['comments'] = $comments;

        $info['taskUserRole'] = $taskUserRole;

        $info['updateTaskItemsId'] = $updateTaskItemsId;

        $return = array();
        if ($json) {
            $return['html'] = $this->renderView('SDTaskBundle:Task:taskView.html.twig', $info);
            $return['success'] = 1;

            return new Response(json_encode($return));
        } else {

            return $this->render('SDTaskBundle:Task:taskView.html.twig', $info);
        }
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

        $tasksUserRole = $tasksUserRoleRepo->getEntitiesListFilter1($filterArray);

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
                $filterArray['role'] = $filter['role'][0];
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
        $user = $this->getUser();
        $userId = $user->getId();

        $tasksUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->findBy(
            array (
                'task' => $taskUserRole->getTask()
            )
        );

        $accesses = array();
        foreach ($tasksUserRole as $taskUserRoleFromTask) {
            $role = $taskUserRoleFromTask->getRole();
            if ($role == 'controller') {
                $taskUserRoles['taskUserRoleController'][] = $taskUserRoleFromTask;
            } elseif ($role == 'performer') {
                $taskUserRoles['taskUserRolePerformer'][] = $taskUserRoleFromTask;
            } elseif ($role == 'matcher') {
                $taskUserRoles['taskUserRoleMatcher'][] = $taskUserRoleFromTask;
            } elseif ($role == 'author') {
                $taskUserRoles['taskUserRoleAuthor'][] = $taskUserRoleFromTask;
            } elseif ($role == 'viewer') {
                $taskUserRoles['taskUserRoleViewer'][] = $taskUserRoleFromTask;
            }

            if ($taskUserRoleFromTask->getUser() == $user) {
                $accesses[] = TaskAccessFactory::createAccess($em, $taskUserRoleFromTask);
            }
        }

        $matchingInfo = array();
        $taskCommitRepo = $em->getRepository('SDTaskBundle:TaskCommit');
        if (isset($taskUserRoles['taskUserRoleMatcher'])) {
            foreach ($taskUserRoles['taskUserRoleMatcher'] as $key => $tur) {

                $taskCommit = $taskCommitRepo->findOneBy(array(
                    'taskUserRole' => $tur
                ), array(
                    'id' => 'DESC'
                ));

                if (!$taskCommit) {
                    $matchingInfo[$key] = 'none';
                } else {
                    if ($taskCommit->getStage() == 'refused_sign_up') {
                        $matchingInfo[$key] = 'refused';
                    } elseif ($taskCommit->getStage() == 'sign_up') {
                        $matchingInfo[$key] = 'agree';
                    }
                }

            }
        }

        $comment = $this->getLastTaskComment($taskUserRole->getTask()->getId());

        //$user = $this->getUser();

/*        $stuff = $em->getRepository('SDUserBundle:Stuff')
            ->findBy(array(
                'user' => $user
            ));
        if ($stuff) {
            $usersCanBeViewed = $em->getRepository('SDUserBundle:User')->getAllStuff();
        } else {
            $usersCanBeViewed = array($user);
        }*/
        //$usersCanBeViewed = array($user);

        $access = new ComparatorTaskAccess($accesses);

        $info = array_merge($taskUserRoles, array (
            'taskUserRole' => $taskUserRole,
            'matchingInfo' => $matchingInfo,
            'userId' => $userId,
            'comment' => $comment,
            'access' => $access,
            //'usersCanBeViewed' => $usersCanBeViewed
        ));

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

        $this->setIsViewedTaskById($id);

        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    private function setIsViewedTaskById($id) {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('SDTaskBundle:TaskUserRole');
        $taskUserRole = $repository->find($id);
        $task = $taskUserRole->getTask();
        $user = $taskUserRole->getUser();

        $taskUserRolesAll = $repository->findBy(array(
            'task' => $task,
            'user' => $user
        ));

        if ($taskUserRolesAll) {
            foreach ($taskUserRolesAll as $tur) {
                $tur->setIsViewed(true);
                $tur->setIsUpdated(false);
                $em->persist($tur);
            }
        } else {
            //exception (no tur found)
        }

        $em->flush();

        //if all performers viewed, then task is performing
        $this->checkIfCanPerform($id);
        //end if performing

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

        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);
        $task = $taskUserRole->getTask();
        if ($stage == 'checking') {
            $user = $taskUserRole->getUser();
            $controllerRole = $em->getRepository('SDTaskBundle:Role')
                ->findOneBy(array(
                    'name' => 'controller',
                    'model' => 'task'
                ));

            $taskUserRolesController = $em->getRepository('SDTaskBundle:TaskUserRole')->findOneBy(array(
                'task' => $task,
                'user' => $user,
                'role' => $controllerRole
            ));
            if ($taskUserRolesController) {
                $stage = 'done';
            }
        }

        if ($stage == 'done' || $stage == 'undone' || $stage == 'closed' || $stage == 'checking') {
            $this->closeDateRequest($id);
        }

        $translator = $this->get('translator');

        $stageName = $stage;
        $additionalComment = '';
        if ($stage == 'checking') {
            $additionalComment = $translator->trans('Make task done', array(), 'SDTaskBundle').'. '.
                $translator->trans('Checking by controller', array(), 'SDTaskBundle').'.';
        }

        $stage = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array(
            'name' => $stage,
            'model' => 'task'
        ));

        $comment = $additionalComment."\n".$translator->trans('Changed the task stage', array(), 'SDTaskBundle');
        $stageTrans = $translator->trans($stageName, array(), 'SDTaskBundle');
        $text = $comment.' :'.$stageTrans;
        if ($commentValue) {
            $text .= "\n".$translator->trans('Comment', array(), 'SDTaskBundle').' :'.$commentValue;
        }
        $this->insertComment($id, $text);

        $task->setStage($stage);
        $em->persist($task);
        $em->flush();

        $this->setTaskUpdated($this->getUser(), $task);

        if ($stage == 'done' || $stage == 'undone' || $stage == 'closed') {
            $performerRole = $em->getRepository('SDTaskBundle:Role')
                ->findOneBy(array(
                    'name' => 'performer',
                    'model' => 'task'
                ));

            $taskUserRolesEmail = $em->getRepository('SDTaskBundle:TaskUserRole')
                ->findBy(array(
                    'task' => $task,
                    'role' => $performerRole
                ));

            $this->sendEmail($taskUserRolesEmail, 'close');
        }
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
    public function answerDateAction(Request $request)
    {
        $id = $request->request->get('id');
        $answer = $request->request->get('answer');
        $commentValue = $request->request->get('comment');

        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);

        $task = $taskUserRole->getTask();
        $user = $taskUserRole->getUser();
        $roleRepository = $this->getDoctrine()
            ->getRepository('SDTaskBundle:Role');

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
                $comment .= "\n".$translator->trans('Comment', array(), 'SDTaskBundle').' :'.$commentValue;
            }
            $this->insertComment($id, $comment);

            $performerRole = $roleRepository
                ->findOneBy(array(
                    'name' => 'performer',
                    'model' => 'task'
                ));

            $taskRolePerformer = $em->getRepository('SDTaskBundle:TaskUserRole')->findBy(array(
                'task' => $task,
                'role' => $performerRole
            ));

            $additionalInfo = array('date' => $taskEndDateRequested->getEndDateTime(), 'message' =>$commentValue);
            $this->sendEmail($taskRolePerformer, 'changing_date', $additionalInfo);

        } else {
            $answerStageName = 'rejected';
            $comment = $translator->trans('Rejected the date request', array(), 'SDTaskBundle');
            if ($commentValue) {
                $comment .= "\n".$translator->trans('Comment', array(), 'SDTaskBundle').' :'.$commentValue;
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

        $this->setTaskUpdated($this->getUser(), $task);

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
    public function addCommentAction(Request $request)
    {
        $id = $request->request->get('id');
        $commentValue = $request->request->get('comment');

        $this->insertComment($id, $commentValue);
        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * Insert resolution
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addResolutionAction(Request $request)
    {
        $id = $request->request->get('id');
        $commentValue = $request->request->get('comment');
        $translator = $this->get('translator');

        if ($commentValue) {
            $comment = $translator->trans('Resolution', array(), 'SDTaskBundle').': '.$commentValue;
        }

        $this->insertComment($id, $comment);

        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);

        $roleRepository = $this->getDoctrine()
            ->getRepository('SDTaskBundle:Role');

        $performerRole = $roleRepository
            ->findOneBy(array(
                'name' => 'performer',
                'model' => 'task'
            ));

        $taskUserRolePerformer = $em->getRepository('SDTaskBundle:TaskUserRole')->findBy(
            array (
                'task' => $taskUserRole->getTask(),
                'role' => $performerRole
            )
        );

        $this->sendEmail($taskUserRolePerformer, 'resolution', array('resolution' => $commentValue));

        $this->setTaskUpdated($this->getUser(), $taskUserRole->getTask());

        $return['success'] = 1;

        return new Response(json_encode($return));
    }


    /**
     * @param int    $idTaskUserRole
     * @param string $commentValue
     * @param string $model
     */
    protected function insertComment($idTaskUserRole, $commentValue, $model = 'Task', $additional = null)
    {
        $taskService = $this->get('task.service');
        $taskService->insertCommentToTask($idTaskUserRole, $commentValue, $model, $additional);
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
        $access = TaskAccessFactory::createAccess($em, $taskUserRole);
        $info['access'] = $access;

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
        $user = $taskUserRole->getUser();
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

        $roleRepository = $this->getDoctrine()
            ->getRepository('SDTaskBundle:Role');

        $controllerRole = $roleRepository
            ->findOneBy(array(
                'name' => 'controller',
                'model' => 'task'
            ));

        $taskUserRoleController = $em->getRepository('SDTaskBundle:TaskUserRole')->findOneBy(array(
            'task' => $task,
            'user' => $user,
            'role' => $controllerRole
        ));

        if ($taskUserRole->getRole() == 'controller' || $taskUserRoleController) {
            $newTaskEndDate->setStage($stageDate); //set without request, coz CONTROLLER did it, behold)
            $comment = $translator->trans('Changed the end date', array(), 'SDTaskBundle').
                ' :'.$newDate->format('d-m-Y H:i');
            if ($commentValue) {
                $comment .= "\n".$translator->trans('Comment', array(), 'SDTaskBundle').' :'.$commentValue;
            }

            $this->insertComment($id, $comment);
            //email to performer about changing date

            $performerRole = $roleRepository
                ->findOneBy(array(
                    'name' => 'performer',
                    'model' => 'task'
                ));

            $taskRolePerformer = $em->getRepository('SDTaskBundle:TaskUserRole')->findBy(array(
                'task' => $task,
                'role' => $performerRole
            ));
            $this->sendEmail($taskRolePerformer, 'changing_date', array('date' => $newDate, 'message' =>$commentValue));

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
                $comment .= "\n".$translator->trans('Comment', array(), 'SDTaskBundle').' :'.$commentValue;
            }

            $this->insertComment($id, $comment);
            //email to controller about request
            $taskRoleController = $em->getRepository('SDTaskBundle:TaskUserRole')->findBy(array(
                'task' => $task,
                'role' => $controllerRole
            ));
            $this->sendEmail($taskRoleController, 'date_request', array('date' => $newDate, 'message' =>$commentValue));
        }
        // end comment block //
        $newTaskEndDate->setTask($task);
        $newTaskEndDate->setChangeDateTime(new \DateTime());

        $em->persist($newTaskEndDate);

        $em->persist($task);
        $em->flush();

        $this->setTaskUpdated($this->getUser(), $task);

        $return['success'] = 1;

        return new Response(json_encode($return));
    }


    /**
    * Function to handle the ajax queries from editable elements
    *
    * @return mixed[]
    */
    public function editableTaskAction()
    {
        $pk = $this->get('request')->request->get('pk');
        $value = $this->get('request')->request->get('value');
        $name = $this->get('request')->request->get('name');

        $em = $this->getDoctrine()->getManager();

        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($pk);

        $task = $taskUserRole->getTask();
        $translator = $this->get('translator');
        if ($name == 'title') {
            $task->setTitle($value);
            $em->persist($task);
            $comment = $translator->trans('Changed the title', array(), 'SDTaskBundle').
                ' :'.$value;

        } elseif ($name == 'description') {
            $task->setDescription($value);
            $em->persist($task);
            $comment = $translator->trans('Changed the description', array(), 'SDTaskBundle').
                ' :'.$value;

        } elseif ($name == 'date') {

            $newDate = new \DateTime($value);

            $taskEndDate = $em->getRepository('SDTaskBundle:TaskEndDate')->findOneBy(array (
                'task' => $task
            ));
            $taskEndDate->setEndDateTime($newDate);
            $em->persist($taskEndDate);

            $comment = $translator->trans('Changed the end date', array(), 'SDTaskBundle').
                ' :'.$newDate->format('d-m-Y H:i');

        }

        $this->insertComment($pk, $comment);

        $em->flush();

        $this->setTaskUpdated($this->getUser(), $task);

        $return['success'] = 1;

        return new Response(json_encode($return));
    }


    /**
     * @param Request $request
     *
     * @return Response
     */
    public function signUpTaskAction(Request $request)
    {
        $id = $request->request->get('id');
        $status = $request->request->get('status');
        $commentValue = $request->request->get('comment');

        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $roleRepository = $em->getRepository('SDTaskBundle:Role');

        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);
        $task = $taskUserRole->getTask();
        $user = $taskUserRole->getUser();
        $role = $taskUserRole->getRole();

        if ($role != 'matcher') {
            /*if user matched from other role*/
            /* need to find matcher */
            $matcherRole = $roleRepository
                ->findOneBy(array(
                    'name' => 'matcher',
                    'model' => 'task'
                ));

            $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->findOneBy(array(
                'task' => $task,
                'user' => $user,
                'role' => $matcherRole
            ));
            if (!$taskUserRole) {
                var_dump('exception');
            }
        }

        $taskCommitRepo = $em->getRepository('SDTaskBundle:TaskCommit');
        $taskCommit = $taskCommitRepo->findOneBy(array(
            'taskUserRole' => $taskUserRole
        ));

        $needToCheckSignUps = false;
        if ($status) {
            $stageName = 'sign_up';
            $comment = $translator->trans('Signed up', array(), 'SDTaskBundle');
            $needToCheckSignUps = true;
        } else {
            $stageName = 'refused_sign_up';
            $comment = $translator->trans('Refused to signed up', array(), 'SDTaskBundle');
        }
        $stage = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array (
            'name' => $stageName,
            'model' => 'task_commit',
        ));


        if (!$taskCommit) {
            $taskCommit = new TaskCommit();
        }
        $taskCommit->setStage($stage);
        $taskCommit->setTaskUserRole($taskUserRole);

        $em->persist($taskCommit);

        if ($commentValue) {
            $comment .= "\n".$translator->trans('Comment', array(), 'SDTaskBundle').' :'.$commentValue;
        }
        $this->insertComment($id, $comment, 'Task', 'commit');

        $em->flush();

        /*START need to send email, to inform author*/
        $authorRole = $roleRepository
            ->findOneBy(array(
                'name' => 'author',
                'model' => 'task'
            ));

        $authorTaskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->findBy(array(
            'task' => $task,
            'role' => $authorRole
        ));

        $this->sendEmail($authorTaskUserRole, 'matching', array('status'=>$status, 'message'=>$commentValue));
        /*END need to send email, to inform author*/

        if ($needToCheckSignUps) {
            /*perhaps it is time to perform task already*/
            $matcherRole = $roleRepository
                ->findOneBy(array(
                    'name' => 'matcher',
                    'model' => 'task'
                ));

            $taskUserRoleMatcher = $em->getRepository('SDTaskBundle:TaskUserRole')->findBy(
                array (
                    'task' => $taskUserRole->getTask(),
                    'role' => $matcherRole
                )
            );

            $canBePerforming = true;
            foreach ($taskUserRoleMatcher as $taskUserRoleCheck) {
                $taskCommit = $taskCommitRepo->findOneBy(array(
                    'taskUserRole' => $taskUserRoleCheck
                ));
                if (!$taskCommit) {
                    $canBePerforming = false;
                    break;
                }
                if ($taskCommit->getStage() == 'refused_sign_up') {
                    $canBePerforming = false;
                    break;
                }
            }

            if ($canBePerforming) {
                $task = $taskUserRole->getTask();
                $stageCreated = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array (
                    'name' => 'created',
                    'model' => 'task',
                ));
                $task->setStage($stageCreated);

                $em->persist($task);
                $em->flush();
                //sending email

                $controllerRole = $roleRepository
                    ->findOneBy(array(
                        'name' => 'controller',
                        'model' => 'task'
                    ));
                $taskUserRoleController = $em->getRepository('SDTaskBundle:TaskUserRole')->findBy(
                    array (
                        'task' => $taskUserRole->getTask(),
                        'role' => $controllerRole
                    )
                );
                $this->sendEmail($taskUserRoleController, 'new');

                $performerRole = $roleRepository
                    ->findOneBy(array(
                        'name' => 'performer',
                        'model' => 'task'
                    ));
                $taskUserRolePerformer = $em->getRepository('SDTaskBundle:TaskUserRole')->findBy(
                    array (
                        'task' => $taskUserRole->getTask(),
                        'role' => $performerRole
                    )
                );
                $this->sendEmail($taskUserRolePerformer, 'new');

                //end sending email

            }
        }

        $this->setTaskUpdated($user, $task);

        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * @param SD/TaskBundle/Entity/TaskUserRole[] $taskUserRoles
     * @param string                              $type
     * @param array                               $additionalInfo
     */
    private function sendEmail($taskUserRoles, $type, $additionalInfo = null)
    {
        $taskService = $this->get('task.service');
        $taskService->sendEmailInform($taskUserRoles, $type, $additionalInfo);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function deleteFileAction(Request $request)
    {
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();

        $taskFile = $em->getRepository('SDTaskBundle:TaskFile')->find($id);

        //comment to deleted file
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->findOneBy(array(
                'task' => $taskFile->getTask(),
                'user' => $taskFile->getUser()
            ));

        $taskService = $this->get('task.service');
        $translator = $this->get('translator');
        $commentValue = $translator->trans('Deleted file', array(), 'SDTaskBundle').': '.$taskFile->getName();
        $taskService->insertCommentToTask($taskUserRole->getId(), $commentValue);


        $em->remove($taskFile);
        $em->flush();

        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function addViewerAction(Request $request)
    {
        $id = $request->request->get('id');
        $idUser = $request->request->get('viewer');
        $role = $request->request->get('role');

        return $this->addTaskUserRole($id, $idUser, $role);
    }

    private function addTaskUserRole($idTaskUserRole, $idUser, $role) {
        $id = $idTaskUserRole;

        $em = $this->getDoctrine()->getManager();

        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);

        $task = $taskUserRole->getTask();

        $userAdd = $em->getRepository('SDUserBundle:User')->find($idUser);
        $AddRole = $em->getRepository('SDTaskBundle:Role')
            ->findOneBy(array(
                'name' => $role,
                'model' => 'task'
            ));

        $checkTaskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->findOneBy(array(
            'task' => $task,
            'user' => $userAdd
        ));

        if ($checkTaskUserRole) {
            $return['success'] = 0;
            $return['error'] = 'already_in_task';

            return new Response(json_encode($return));
        }

        $taskUserRoleAdd = new TaskUserRole();
        $taskUserRoleAdd->setUser($userAdd);
        $taskUserRoleAdd->setRole($AddRole);
        $taskUserRoleAdd->setTask($task);
        $taskUserRoleAdd->setIsViewed(false);
        $taskUserRoleAdd->setIsUpdated(false);

        $em->persist($taskUserRoleAdd);
        $em->flush();
        $em->refresh($taskUserRoleAdd);

        $this->sendEmail(array($taskUserRoleAdd), 'new');
        //add comment
        $taskService = $this->get('task.service');
        $translator = $this->get('translator');
        $commentValue = $translator->trans('Added matcher', array(), 'SDTaskBundle').': '.$userAdd;
        $taskService->insertCommentToTask($taskUserRole->getId(), $commentValue);

        $this->setTaskUpdated($this->getUser(), $task);
        $return['success'] = 1;

        return new Response(json_encode($return));

    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function setPatternAction(Request $request)
    {
        $id = $request->request->get('id');

        $em = $this->getDoctrine()->getManager();

        $pattern = $em->getRepository('SDTaskBundle:TaskPattern')->find($id);

        $return['description'] = $pattern->getDescription();
        $return['title'] = $pattern->getTitle();
        $responsible = $pattern->getResponsible();
        $return['responsible'] = null;
        if ($responsible) {
            $return['responsible'] = $responsible->getId();
        }

        $patternRoles = $em->getRepository('SDTaskBundle:PatternUserRole')->findBy(array(
            'taskPattern' => $pattern
        ));

        if ($patternRoles) {
            foreach ($patternRoles as $patternRole) {
                $role = $patternRole->getRole()->getName();
                $return[$role][] = $patternRole->getUser()->getId();
            }
        }

        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * getAllStuffAjaxAction
     * 
     * @param Request $request
     *
     * @return Response
     */
    public function getAllStuffAjaxAction(Request $request) {

        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $stuff = $em->getRepository('SDUserBundle:Stuff')
            ->findBy(array(
                'user' => $user
            ));
        if ($stuff) {
            $usersCanBeViewed = $em->getRepository('SDUserBundle:User')->getAllStuff();
        } else {
            $usersCanBeViewed = array($user);
        }

        $result = array();

        foreach ($usersCanBeViewed as $object) {
            $id = $object->getId();
            $string = (string) $object;

            $result[] = array(
                'id' => $id,
                'value' => $id,
                'name' => $string,
                'text' => $string
            );
        }

        return new Response(json_encode($result));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function printAction($id) {
        //$id = 1081;

        $taskUserRole = $this->getDoctrine()
            ->getRepository('SDTaskBundle:TaskUserRole')
            ->find($id);
        $user = $this->getUser();
        if ($taskUserRole->getUser() != $user) {

            return $this->render('SDTaskBundle:Task:noTaskAccess.html.twig', array());
        }

        $info = $this->getTaskUserRoleInfo($id);

        $idTask = $taskUserRole->getTask()->getId();

        $commentRepository = $this->getDoctrine()
            ->getRepository('SDTaskBundle:Comment');

        $comments = $commentRepository->findBy(array (
            'model' => 'Task',
            'modelId' => $idTask
        ), array (
            'createDatetime' => 'DESC'
        ));

        $info['comments'] = $comments;

        $info['taskUserRole'] = $taskUserRole;

        return $this->render('SDTaskBundle:Task:taskPrint.html.twig', $info);
    }

    private function setTaskUpdated($exceptUser, $task) {
        $taskUserRoles = $this->getDoctrine()
            ->getRepository('SDTaskBundle:TaskUserRole')
            ->findBy(array(
                'task' => $task
            ));
        $em = $this->getDoctrine()->getManager();

        foreach ($taskUserRoles as $taskUserRole) {
            $user = $taskUserRole->getUser();
            if ($user != $exceptUser) {
                $taskUserRole->setIsUpdated(true);
                $em->persist($taskUserRole);
            }
        }

        $task->setEditedDate(new \DateTime());
        $em->persist($task);

        $em->flush();
    }

    public function replyAction(Request $request) {
        $idParent = $request->request->get('id');

        $value = $request->request->get('value');

        $em = $this->getDoctrine()->getManager();

        $comment = $em->getRepository('SDTaskBundle:Comment')->find($idParent);
        $modelId = $comment->getModelId();

        $commentNew = new Comment();
        $commentNew->setValue($value);
        $commentNew->setCreateDatetime(new \DateTime());
        $commentNew->setModel('Task');
        $commentNew->setModelId($modelId);
        $commentNew->setUser($this->getUser());

        $comment->addChild($commentNew);

        $em->persist($comment);
        $em->persist($commentNew);

        $em->flush();

        $task = $em->getRepository('SDTaskBundle:Task')->find($modelId);
        $this->setTaskUpdated($this->getUser(), $task);

        $return['success'] = 1;

        return new Response(json_encode($return));
    }
}
