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
        $em = $this->getDoctrine()->getManager();
        $service = $this->get('lists_department.service');
        $serviceITDoors = $this->get('itdoors_common.base.service');
        $access = $service->checkAccess($this->getUser());

        if (!$access->canUse()) {
            throw new Exception('No Access', 403);
        }
        $nameSpaceDepartment = $this->nameSpaceDepartment.'_appointment';
        $filters = $serviceITDoors->getFilters($nameSpaceDepartment);
        $departmens = array();
//        if (empty($filters) || empty($filters['user'])) {
//            $filters['isFired'] = 'No fired';
//            $departmens = array();
//        } else {
//            /** @var \Doctrine\ORM\Query */
//            $departments = $em->getRepository('ListsDepartmentBundle:Department')->getFilteredDepartments($filters);
//
//            $userId = $filters['user'];
//
//                /** @var \SD\UserBundle\Entity\User $user */
//                $user = $em->getRepository('SDUserBundle:User')->find($userId);
//
//                $stuffDepartmensQuery = $user->getStuff()->getStuffDepartments(array(''));
//                $organizations = array();
//                foreach ($stuffDepartmensQuery as $stuff) {
//                    $departments = $stuff->getDepartments();
//                    $organizations[$departments->getOrganizationId()][$departments->getId()] = $departments;
//                }
//                /* order by organization */
//                foreach ($organizations as $organization) {
//                    foreach ($organization as $key => $dep) {
//                        $departmensQuery[$key] = $dep;
//                    }
//                }
//        }

        return $this->render('ListsDepartmentBundle:Department:appointment.html.twig', array (
                'nameSpaceDepartment' => $nameSpaceDepartment,
                'access' => $access
        ));
    }
}
