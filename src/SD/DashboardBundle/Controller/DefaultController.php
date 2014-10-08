<?php

namespace SD\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * DefaultController
 */
class DefaultController extends Controller
{
    /**
     * indexAction
     *
     * @return string
     */
    public function indexAction ()
    {
        return $this->render('SDDashboardBundle:Default:index.html.twig');
    }
    /**
     * generateTasksCalendarAction
     *
     * @return string
     */
    public function generateTasksCalendarAction ()
    {
        return $this->render('SDDashboardBundle:Default:tasksCalendar.html.twig');
    }
}
