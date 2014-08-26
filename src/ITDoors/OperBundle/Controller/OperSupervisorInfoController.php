<?php

namespace ITDoors\OperBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use ITDoors\OperBundle\Services\AccessService;
use Symfony\Component\HttpFoundation\Response;

/**
 * OperSupervisorInfoController class
 *
 * Default controller for oper page
 */
class OperSupervisorInfoController extends OperCoworkerInfoController
{

    public function exportExcelCoworkerAction() {
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

        $query = $monthInfoRepository->getFilteredCoworkersExport($allowedDepartments, $month, $year, $filters);

        $coworkers = $query->getArrayResult();


/*        foreach ($coworkers as $key=>$coworker) {
            $infoSalary = $this->forward('ITDoorsOperBundle:OperSchedule:getSumsCoworker', array(
                'date' => $year.'-'.$month,
                'idCoworker' => $coworker['idCoworker'],
                'idReplacement' => $coworker['replacementId'],
                'idDepartment' => $coworker['idDepartment']
            ));
            $coworkers[$key] = array_merge($infoSalary, $coworkers[$key]);


        }*/

        $serviceExport = $this->get('itdoors_common.export.service');

        $excelObject = $serviceExport->getExcel($coworkers);



        $response = $serviceExport->getResponse($excelObject, 'Contacts');

        return $response;
    }
}