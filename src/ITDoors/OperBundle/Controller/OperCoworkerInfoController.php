<?php

namespace ITDoors\OperBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
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
    public function indexAction()
    {
        /** @var AccessService $accessService */
        $accessService = $this->get('access.service');
        $allowedDepartments = $accessService->getAllowedDepartmentsId();

        $this->addToSessionValues('idDepartment', $allowedDepartments, 'param', 'oper.bundle.department');

        return $this->render('ITDoorsOperBundle:Coworker:index.html.twig');
    }

    /**
     * coworkerTableAction
     *
     * @return mixed[]
     */
    public function coworkerTableAction()
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

        $query = $monthInfoRepository->getFilteredCoworkers($allowedDepartments, $month, $year, $filters);

        $coworkers = $query->getResult();
        $infoSumSalary = array();
        $commonService = $this->get('common_oper.service');
        foreach ($coworkers as $coworker) {
            $id = $coworker['id'];
            $replacementId = $coworker['replacementId'];
            $idDepartment = $coworker['idDepartment'];
            $infoSumSalary['coworker_'.$id] = $commonService->getSumsCoworker($year.'-'.$month, $id, $replacementId, $idDepartment);

        }
        $canEdit = $accessService->checkIfCanEdit();

        return $this->render('ITDoorsOperBundle:Coworker:coworkerTable.html.twig', array(
            'coworkers' => $coworkers,
            'month' => $month,
            'year' => $year,
            'infoSumSalary' => $infoSumSalary,
            'canEdit' => $canEdit
        ));

    }

}