<?php

namespace SD\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TaskController extends Controller
{
    public function indexAction()
    {
        return $this->render('SDTaskBundle:Task:index.html.twig');
    }
}
