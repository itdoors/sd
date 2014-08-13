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
}
