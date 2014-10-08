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
    /**
     * @return mixed
     */
    public function exportExcelCoworkerAction ()
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

        //$orders = $this->getOrdering($filterNamespace);

        $query = $monthInfoRepository->getFilteredCoworkersExport($allowedDepartments, $month, $year, $filters);

        $coworkers = $query->getArrayResult();


        foreach ($coworkers as $key => $coworker) {
            $infoSalary = $this->getSumsCoworker(
                $year . '-' . $month,
                $coworker['idCoworker'],
                $coworker['replacementId'],
                $coworker['idDepartment']
            );
            $officiallAcrual =
                $infoSalary['accrual']['officially']['plus'] - $infoSalary['accrual']['officially']['minus'];
            $notOfficiallAcrual =
                $infoSalary['accrual']['notOfficially']['plus'] - $infoSalary['accrual']['notOfficially']['minus'];
            unset($infoSalary['accrual']);
            $infoSalary['officiallAcrual'] = $officiallAcrual;
            $infoSalary['notOfficiallAcrual'] = $notOfficiallAcrual;


            $coworkers[$key] = array_merge($coworkers[$key], $infoSalary);
            unset($coworkers[$key]['idReplacement']);
            unset($coworkers[$key]['idMonthInfo']);
            unset($coworkers[$key]['canEdit']);
            unset($coworkers[$key]['replacementId']);
            unset($coworkers[$key]['replacementType']);
            unset($coworkers[$key]['replacementType']);
            unset($coworkers[$key]['idCoworker']);
            //var_dump($coworkers[$key]);die();
        }

        $serviceExport = $this->get('itdoors_common.export.service');

        $excelObject = $serviceExport->getExcel($coworkers);



        $response = $serviceExport->getResponse($excelObject, 'Coworkers');

        return $response;
    }
    /**
     * @param string  $date
     * @param integer $idCoworker
     * @param integer $idReplacement
     * @param integer $idDepartment
     *
     * @return array
     */
    private function getSumsCoworker ($date, $idCoworker, $idReplacement, $idDepartment)
    {
        list($year, $month) = explode('-', $date);

        /** @var $grafikRepository \Lists\GrafikBundle\Entity\GrafikRepository   */
        $grafikRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:Grafik');

        /** @var  $monthInfoRepository \Lists\DepartmentBundle\Entity\departmentPeopleMonthInfoRepository */
        $monthInfoRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeopleMonthInfo');

        $monthInfo = $monthInfoRepository->findOneBy(array (
            'departmentPeople' => $idCoworker,
            'year' => $year,
            'month' => $month,
            'replacementType' => 'r',
            'departmentPeopleReplacement' => $idReplacement
        ));

        $idMonthInfo = $monthInfo->getId();


        if (isset($monthInfo)) {
            $salaryOfficially = $monthInfo->getSalaryOfficially();
            if (!$salaryOfficially) {
                $salaryOfficially = 0;
            }

            $realSalary = $monthInfo->getRealSalary();
            if (!$realSalary) {
                $realSalary = 0;
            }
        }

        $officiallyTotal = $grafikRepository->getSumTotalOfficially(
            $year,
            $month,
            $idDepartment,
            $idCoworker,
            $idReplacement
        );
        $notOfficiallyTotal = $grafikRepository->getSumTotalNotOfficially(
            $year,
            $month,
            $idDepartment,
            $idCoworker,
            $idReplacement
        );

        $accrual = $this->getTotalOnceOnlyAccruals(
            $month,
            $year,
            $idCoworker
        );

        $plannedAccrualRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:PlannedAccrual');

        $plannedAccrual = $plannedAccrualRepository->findOneBy(
            array (
                'departmentPeople' => $idCoworker,
                'code' => 'UU',
                'isActive' => true
            ),
            array (
                'period' => 'desc'
            )
        );

        if ($plannedAccrual) {
            $plannedAccrualValue = $plannedAccrual->getValue();
        } else {
            $plannedAccrualValue = 0;
        }
        $salaryNotOfficially = $monthInfo->getSalaryNotOfficially();
        if (!$salaryNotOfficially) {
            $salaryNotOfficially = $plannedAccrualValue;
            $salaryNotOfficially += $accrual['notOfficially']['plus'];
            $salaryNotOfficially -= $accrual['notOfficially']['minus'];
        }

        $totalSalary = $salaryOfficially + $salaryNotOfficially;
        $canEdit = $this->checkIfCanEdit();
        $return = array (
            'sumOfficiallyHours' => $officiallyTotal,
            'sumNotOfficiallyHours' => $notOfficiallyTotal,
            'plannedAccrual' => $plannedAccrualValue,
            'accrual' => $accrual,
            'salaryOfficially' => $salaryOfficially,
            'idCoworker' => $idCoworker,
            'idReplacement' => $idReplacement,
            'realSalary' => $realSalary,
            'salaryNotOfficially' => $salaryNotOfficially,
            'totalSalary' => $totalSalary,
            'idMonthInfo' => $idMonthInfo,
            'canEdit' => $canEdit,
            'idDepartment' => $idDepartment
        );

        return $return;
    }
    /**
     * @param integer $month
     * @param integer $year
     * @param integer $idCoworker
     *
     * @return mixed[]
     */
    private function getTotalOnceOnlyAccruals ($month, $year, $idCoworker)
    {

        /** @var  $monthInfoRepository \Lists\DepartmentBundle\Entity\departmentPeopleMonthInfoRepository */
        $monthInfoRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeopleMonthInfo');

        $monthInfo = $monthInfoRepository->findOneBy(array (
            'departmentPeople' => $idCoworker,
            'year' => $year,
            'month' => $month,
            'replacementType' => 'r',
            //'departmentPeopleReplacement' => 0
        ));


        $onceOnlyAccrualRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:OnceOnlyAccrual');


        $accrual['officially'] = array ();
        $accrual['notOfficially'] = array ();

        $accrual['officially']['plus'] = 0;
        $accrual['notOfficially']['plus'] = 0;

        $accrual['officially']['minus'] = 0;
        $accrual['notOfficially']['minus'] = 0;

        if (!$monthInfo) {

            return $accrual;
        }
        $onceOnlyAccruals = $onceOnlyAccrualRepository->findBy(array (
            'departmentPeopleMonthInfo' => $monthInfo->getId()
        ));

        foreach ($onceOnlyAccruals as $onceOnlyAccrual) {
            if ($onceOnlyAccrual->getCode() == 'uu') {
                $key = 'notOfficially';
            } else {
                $key = 'officially';
            }
            if ($onceOnlyAccrual->getType() == 'fine') {
                $accrual[$key]['minus'] += $onceOnlyAccrual->getValue();
            } else {
                $accrual[$key]['plus'] += $onceOnlyAccrual->getValue();
            }
        }

        return $accrual;
    }
    /**
     * @return bool
     */
    private function checkIfCanEdit ()
    {
        $canEdit = !$this->getUser()->hasRole('ROLE_SUPERVISOR');

        return $canEdit;
    }
}
