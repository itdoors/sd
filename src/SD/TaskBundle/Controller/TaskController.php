<?php

namespace SD\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function indexAction()
    {
        return $this->render('SDTaskBundle:Task:index.html.twig');
    }



    public function taskTableAction() {
        $user = $this->getUser();

        $tasksUserRole = $this->getDoctrine()
            ->getRepository('SDTaskBundle:TaskUserRole')
            ->findBy(
                array(
                    'user' => $user
                ),
                array(
                    'isViewed' => 'ASC',
                    'id' => 'DESC'
                )
            );

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

        //if all performers viewed, then task is performing
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
        }
        //end if performing
        $em->persist($taskUserRole);
        $em->flush();

        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    public function taskStageUpdateAction(Request $request) {
        $id = $request->request->get('id');
        $stage = $request->request->get('stage');

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
}
