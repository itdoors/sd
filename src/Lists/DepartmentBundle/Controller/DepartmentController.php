<?php

namespace Lists\DepartmentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * Class DepartmentController
 */
class DepartmentController extends Controller
{
    protected $nameSpaceDepartment = 'lists_department';
    /**
     * appointmentAction
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function appointmentAction ()
    {
        $service = $this->get('lists_department.service');
        $access = $service->checkAccess($this->getUser());

        if (!$access->canUse()) {
            throw new Exception('No Access', 403);
        }
        $nameSpaceDepartment = $this->nameSpaceDepartment.'_appointment';
        
        return $this->render('ListsDepartmentBundle:Department:appointment.html.twig', array (
                'nameSpaceDepartment' => $nameSpaceDepartment,
                'access' => $access
        ));
    }
    /**
     * appointmentListAction
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function appointmentListAction ()
    {
        $em = $this->getDoctrine()->getManager();
        $service = $this->get('lists_department.service');
        $serviceITDoors = $this->get('it_doors_ajax.base_filter_service');
        $access = $service->checkAccess($this->getUser());
        $claimtypes = $em->getRepository('SDUserBundle:Claimtype')->findAll();

        if (!$access->canUse()) {
            throw new Exception('No Access', 403);
        }
        $nameSpaceDepartment = $this->nameSpaceDepartment.'_appointment';
        $filters = $serviceITDoors->getFilters($nameSpaceDepartment);
        $departments = array();
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $departments = array();
        } else {
            $departments = $em->getRepository('ListsDepartmentBundle:Departments')->getFilteredDepartments($filters)->getResult();
        }

        return $this->render('ListsDepartmentBundle:Department:appointmentList.html.twig', array (
                'nameSpaceDepartment' => $nameSpaceDepartment,
                'departments' => $departments,
                'access' => $access,
                'filters' => $filters,
                'claimtypes' => $claimtypes
        ));
    }
}
