<?php

namespace ITDoors\OperBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use ITDoors\OperBundle\Services\AccessService;
use Symfony\Component\HttpFoundation\Response;

/**
 * OperCoworkerInfoController class
 *
 * Default controller for oper page
 */
class OperCoworkerInfoController extends BaseFilterController
{

    protected $filterNamespace = 'ajax.namespace.oper.department.schedule';

    /**
     * indexAction
     *
     * @return mixed[]
     */
    public function indexAction ()
    {
        $year = intval(date('Y'));
        $month = intval(date('m'));

        $filterNamespace = $this->container->getParameter($this->getNamespace());
        $filters = $this->getFilters($filterNamespace);

        if (!array_key_exists('year', $filters) || $filters['year'] == null || $filters['year'] == '') {
            $this->addToFilters('year', $year, $filterNamespace);
        }
        if (!array_key_exists('month', $filters) || $filters['month'] == null || $filters['month'] == '') {
            $this->addToFilters('month', $month, $filterNamespace);
        }
        //////////////////////////

        /** @var AccessService $accessService */
        $accessService = $this->get('access.service');
        $allowedDepartments = $accessService->getAllowedDepartmentsId();
        $this->addToSessionValues('idDepartment', $allowedDepartments, 'param', 'oper.bundle.department');

        $user = $this->getUser();
        $checkSupervisor = $user->hasRole('ROLE_SUPERVISOR');

        return $this->render('ITDoorsOperBundle:Coworker:index.html.twig', array (
                'supervisor' => $checkSupervisor
        ));
    }
    /**
     * coworkerTableAction
     *
     * @return mixed[]
     */
    public function coworkerTableAction ()
    {

        $filterNamespace = $this->container->getParameter($this->getNamespace());
        $filters = $this->getFilters($filterNamespace);

        $year = intval(date('Y'));
        $month = intval(date('m'));

        if (isset($filters['year']) && $filters['year']) {
            $year = $filters['year'];
        }
        if (isset($filters['month']) && $filters['month']) {
            $month = $filters['month'];
        }


        /** @var AccessService $accessService */
        $accessService = $this->get('access.service');
        $allowedDepartments = $accessService->getAllowedDepartmentsId();

        //$allowedDepartments = array(2111);
        $this->addToSessionValues('idDepartment', $allowedDepartments, 'param', 'oper.bundle.department');


        /** @var  $monthInfoRepository \Lists\DepartmentBundle\Entity\departmentPeopleMonthInfoRepository */
        $monthInfoRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeopleMonthInfo');

        $orders = $this->getOrdering($filterNamespace);

        $query = $monthInfoRepository->getFilteredCoworkers($allowedDepartments, $month, $year, $filters, $orders);

        $coworkers = $query->getResult();

        /*        $infoSumSalary = array();
          $commonService = $this->get('common_oper.service');

          foreach ($coworkers as $coworker) {
          $id = $coworker['id'];
          $replacementId = $coworker['replacementId'];
          $idDepartment = $coworker['idDepartment'];
          //$infoSumSalary['coworker_'.$id] = $commonService->getSumsCoworker($year.'-'.$month, $id, $replacementId, $idDepartment);

          } */

        $canEdit = $accessService->checkIfCanEdit();

        return $this->render('ITDoorsOperBundle:Coworker:coworkerTable.html.twig', array (
                'coworkers' => $coworkers,
                'month' => $month,
                'year' => $year,
                //'infoSumSalary' => $infoSumSalary,
                'canEdit' => $canEdit
        ));
    }
    /**
     * @return Response
     */
    public function coworkerIndexAction ()
    {
        $user = $this->getUser();
        $checkSupervisor = $user->hasRole('ROLE_SUPERVISOR');

        return $this->render('ITDoorsOperBundle:Coworker:coworker.html.twig', array (
                'supervisor' => $checkSupervisor
        ));
    }
    /**
     * @return Response
     */
    public function scheduleIndexAction ()
    {
        $checkSupervisor = $this->getUser()->hasRole('ROLE_SUPERVISOR');

        $coworkerSupervisorPage = false;
        if ($checkSupervisor) {
            $coworkerSupervisorPage = true;
        }

        return $this->render('ITDoorsOperBundle:Coworker:schedule.html.twig', array (
                'coworkerSupervisorPage' => $coworkerSupervisorPage
        ));
    }
    /**
     * @return Response
     */
    public function testTableRenderAction ()
    {
        //////////////////////////
        $data[0]['number'] = 1;
        $data[0]['city'] = 'Kiev';
        $data[0]['visited'] = true;
        $data[0]['cityId'] = 22;

        $data[1]['number'] = 2;
        $data[1]['city'] = 'Paris';
        $data[1]['visited'] = false;
        $data[1]['cityId'] = 25;

        $data[2]['number'] = 3;
        $data[2]['city'] = 'Sochi';
        $data[2]['visited'] = true;
        $data[2]['cityId'] = 23;

        $data[3]['number'] = 4;
        $data[3]['city'] = 'Odessa';
        $data[3]['visited'] = true;
        $data[3]['cityId'] = 27;

        $data[4]['number'] = 5;
        $data[4]['city'] = 'Khmelnitskiy';
        $data[4]['visited'] = false;
        $data[4]['cityId'] = 28;

        /*
          $departmentPeopleRepository = $this->getDoctrine()
          ->getRepository('ListsDepartmentBundle:DepartmentPeople');

          $dps = $departmentPeopleRepository->findBy(array(
          'department' => 2111
          ));
          foreach ($dps as $dp) {
          $data[] = get_object_vars($dp);
          }
         */

        $options = array ();
        //$options['show'] = array('number', 'city', 'visited');
        $options['visited']['type'] = 'checkbox';
        $options['visited']['param'] = array (
            'checked' => 'value',
            'pattern' => '/oleoleole/',
            'index' => 'cityId',
            'class' => 'cool aha',
            'data' => array ('pk' => 5, 'city' => 'olena')
        );

        $options['city']['type'] = 'text';
        $options['city']['param'] = array (
            'ordering' => true
        );

        return $this->render('ITDoorsOperBundle:Coworker:testTable.html.twig', array (
                'options' => $options,
                'data' => $data
        ));
    }
}
