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
        $service = $this->get('sd_dashboard.service');
        $access = $service->checkAccess($this->getUser());

        return $this->render('SDDashboardBundle:Default:index.html.twig', array(
            'access' => $access
        ));
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
