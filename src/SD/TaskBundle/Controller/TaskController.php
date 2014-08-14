<?php

namespace SD\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
                    'isViewed' => 'ASC'
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
    public function taskModalction(Request $request)
    {
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $taskUserRole = $em->getRepository('SDCalendarBundle:TaskUserRole')->find($id);
        $userId = $this->getUser()->getId();
        $return = array();
        $return['html'] = $this->renderView('SDTaskBundle:Task:taskModal.html.twig', array(
            'taskUserRole' => $taskUserRole,
            'userId' => $userId
        ));

        return new Response(json_encode($return));
    }

}
