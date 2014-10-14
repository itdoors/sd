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

        $countTasks = array();
        $countTasks['performer'] = $tasksUserRoleRepo->countTasksByRoleAndUser($user->getId(), 'performer');
        $countTasks['controller'] = $tasksUserRoleRepo->countTasksByRoleAndUser($user->getId(), 'controller');
        $countTasks['matcher'] = $tasksUserRoleRepo->countTasksByRoleAndUser($user->getId(), 'matcher');
        $countTasks['author'] = $tasksUserRoleRepo->countTasksByRoleAndUser($user->getId(), 'author');
        $countTasks['viewer'] = $tasksUserRoleRepo->countTasksByRoleAndUser($user->getId(), 'viewer');
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

        $filterArray['showClosed'] = (bool) $request->request->get('showClosed');

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


        $access = new ComparatorTaskAccess($accesses);

        $info = array_merge($taskUserRoles, array (
            'taskUserRole' => $taskUserRole,
            'matchingInfo' => $matchingInfo,
            'userId' => $userId,
            'comment' => $comment,
            'access' => $access,
            'usersCanBeViewed' => $usersCanBeViewed
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
                $em->persist($tur);
            }
        } else {
            //exception (no tur found)
        }

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

        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);
        $task = $taskUserRole->getTask();

        if ($stage == 'done' || $stage == 'undone' || $stage == 'closed' || $stage == 'checking') {
            $this->closeDateRequest($id);
        }

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
            $text .= "\n".$translator->trans('Comment', array(), 'SDTaskBundle').' :'.$commentValue;
        }
        $this->insertComment($id, $text);

        $task->setStage($stage);
        $em->persist($task);
        $em->flush();

        if ($stage == 'done' || $stage == 'undone' || $stage == 'closed') {
            $taskUserRolesEmail = $em->getRepository('SDTaskBundle:TaskUserRole')
                ->findBy(array(
                    'task' => $task
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
    public function taskChangeDateRequestAction (Request $request)
    {
        $id = $request->request->get('id');
        $value = $request->request->get('value');
        $type = $request->request->get('type');
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
            $newTaskEndDate->setStage($stageDate);
            $comment = $translator->trans('Changed the end date', array(), 'SDTaskBundle').
                ' :'.$newDate->format('d-m-Y H:i');
            if ($commentValue) {
                $comment .= "\n".$translator->trans('Comment', array(), 'SDTaskBundle').' :'.$commentValue;
            }
            $this->insertComment($id, $comment);
        } else {
            $newTaskEndDate->setStage($stageRequest);
            $stageDateRequest = $em->getRepository('SDTaskBundle:Stage')->findOneBy(array (
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
                $comment .= "\n".$translator->trans('Comment', array(), 'SDTaskBundle').' :'.$commentValue;
            }
            $this->insertComment($id, $comment);
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

        $return['success'] = 1;

        return new Response(json_encode($return));
    }


    /**
     * @param int    $idTaskUserRole
     * @param string $commentValue
     * @param string $model
     */
    protected function insertComment($idTaskUserRole, $commentValue, $model = 'Task')
    {
        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($idTaskUserRole);

        $idTask = $taskUserRole->getTask()->getId();
        $user = $this->getUser();
        $comment = new Comment();

        $comment->setValue($commentValue);
        $comment->setCreateDatetime(new \DateTime());
        $comment->setModel($model);
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
                $comment .= "\n".$translator->trans('Comment', array(), 'SDTaskBundle').' :'.$commentValue;
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
                $comment .= "\n".$translator->trans('Comment', array(), 'SDTaskBundle').' :'.$commentValue;
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
        $this->insertComment($id, $comment);

        $em->flush();

        if ($needToCheckSignUps) {
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
        $idViewer = $request->request->get('viewer');

        $em = $this->getDoctrine()->getManager();

        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($id);

        $task = $taskUserRole->getTask();

        $userViewer = $em->getRepository('SDUserBundle:User')->find($idViewer);
        $viewerRole = $em->getRepository('SDTaskBundle:Role')
            ->findOneBy(array(
                'name' => 'viewer',
                'model' => 'task'
            ));

        $checkTaskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->findOneBy(array(
            'task' => $task,
            'user' => $userViewer
        ));

        if ($checkTaskUserRole) {
            $return['success'] = 0;
            $return['error'] = 'already_in_task';

            return new Response(json_encode($return));
        }

        $taskUserRoleViewer = new TaskUserRole();
        $taskUserRoleViewer->setUser($userViewer);
        $taskUserRoleViewer->setRole($viewerRole);
        $taskUserRoleViewer->setTask($task);
        $taskUserRoleViewer->setIsViewed(false);

        $em->persist($taskUserRoleViewer);
        $em->flush();
        $em->refresh($taskUserRoleViewer);

        $this->sendEmail(array($taskUserRoleViewer), 'new');

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
}
