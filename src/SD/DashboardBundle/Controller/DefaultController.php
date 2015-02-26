<?php

namespace SD\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ITDoors\ControllingBundle\Services\ControllingService;
use Lists\ProjectBundle\Service\ProjectService;

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
        
        /** @var ProjectService $serviceProject */
        $serviceProject = $this->get('lists_project.service');
        $accessProject = $serviceProject->checkAccess($this->getUser());

        return $this->render('SDDashboardBundle:Default:index.html.twig', array(
            'access' => $access,
            'accessDogovor' => $accessDogovor,
            'accessControlling' => $accessControlling,
            'accessProject' => $accessProject
        ));
    }
    /**
     * saveSelectedManagerAction
     *
     * @return string
     */
    public function saveSelectedManagerAction (\Symfony\Component\HttpFoundation\Request $reuest)
    {
        $managerId = $reuest->request->get('value');
        
        $session = $this->get('session');
        $session->set('managerForCalendar', $managerId);
        
        return new \Symfony\Component\HttpFoundation\Response(json_encode(array('save'=>'ok')));
    }
    /**
     * generateTasksCalendarAction
     *
     * @return string
     */
    public function generateTasksCalendarAction ()
    {
        $session = $this->get('session');
        $managerForCalendar = $session->get('managerForCalendar', null);
        
        $em = $this->getDoctrine()->getManager();
        if ($managerForCalendar) {
            $managerForCalendar = $em->getRepository('SDUserBundle:User')->find($managerForCalendar);
        } else {
            $managerForCalendar = $this->getUser();
        }
        
        return $this->render('SDDashboardBundle:Default:tasksCalendar.html.twig', array('managerForCalendar' => $managerForCalendar));
    }
}
