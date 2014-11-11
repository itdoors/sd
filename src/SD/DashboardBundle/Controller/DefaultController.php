<?php

namespace SD\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ITDoors\ControllingBundle\Services\ControllingService;

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

        $serviceDogovor = $this->get('lists_dogovor.service');
        $accessDogovor = $serviceDogovor->checkAccess($this->getUser());

        /** @var ControllingService $serviceControlling */
        $serviceControlling = $this->get('it_doors_controlling.service');
        $accessControlling = $serviceControlling->checkAccess($this->getUser());

        return $this->render('SDDashboardBundle:Default:index.html.twig', array(
            'access' => $access,
            'accessDogovor' => $accessDogovor,
            'accessControlling' => $accessControlling
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
