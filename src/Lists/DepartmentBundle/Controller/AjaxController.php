<?php

namespace Lists\DepartmentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AjaxController
 */
class AjaxController extends Controller
{
    protected $nameSpaceDepartment = 'lists_department';
    /**
     * appointmentSaveAction
     * 
     * @param Request $request
     *
     * @return Response
     */
    public function appointmentSaveAction (Request $request)
    {
        $departmensIds = $request->get('departmens');
        $userIds = explode(',', $request->get('userIds'));
        $userRole = $request->get('userRole');
        $claimtypeIds = explode(',', $request->get('typeOrganization'));

        $service = $this->get('lists_department.service');
        $access = $service->checkAccess($this->getUser());
        $result = array();
        if (!$access->canUse()) {
            $result['error'] = 'No Access';
        } else {
            $result['success'] = true;

            $em = $this->getDoctrine()->getManager();
            /** @var \SD\UserBundle\Entity\User $users */
            $users = $em->getRepository('SDUserBundle:User')->findBy(array('id' => $userIds));
            /** @var \SD\UserBundle\Entity\Claimtype $claimtypes */
            $claimtypes = $em->getRepository('SDUserBundle:Claimtype')->findBy(array('id' => $claimtypeIds));
            /** @var \Lists\DepartmentBundle\Entity\Departments $departments */
            $departments = $em->getRepository('ListsDepartmentBundle:Departments')
                ->findBy(array('id' => $departmensIds));

            foreach ($users as $user) {
                $stuffDepartments = $em
                    ->getRepository('SDUserBundle:StuffDepartments')
                    ->findBy(array (
                        'stuff' => $user->getStuff(),
                        'departments' => $departments
                    ));
                foreach ($stuffDepartments as $stuffDepartment) {
                    $em->remove($stuffDepartment);
                }
                $em->flush();
                foreach ($departments as $department) {
                    foreach ($claimtypes as $claimtype) {
                        $stuffDepartment = new \SD\UserBundle\Entity\StuffDepartments();
                        $stuffDepartment->setClaimtypes($claimtype);
                        $stuffDepartment->setDepartments($department);
                        $stuffDepartment->setStuff($user->getStuff());
                        $stuffDepartment->setUserkey($userRole);
                        $em->persist($stuffDepartment);
                    }
                }
                $em->flush();
            }
        }

        return new Response(json_encode($result));
    }
}
