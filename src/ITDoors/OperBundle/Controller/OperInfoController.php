<?php

namespace ITDoors\OperBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Lists\DepartmentBundle\ListsDepartmentBundle;
use PHPExcel_Style_Alignment;


/**
 * OperInfoController class
 *
 * Default controller for oper page
 */
class OperInfoController extends BaseFilterController
{

    protected $filterNamespace = 'ajax.filter.namespace.oper.department.table';

    /**
     * indexAction
     *
     * @return mixed[]
     */
    public function indexAction ()
    {
        /** @var AccessService $accessService */
        $accessService = $this->get('access.service');

        $this->addToSessionValues(
            'idDepartment',
            $accessService->getAllowedDepartmentsId(),
            'param',
            'oper.bundle.department'
        );

        return $this->render('ITDoorsOperBundle:Patterns:index.html.twig', array (
        ));
    }
    /**
     * departmentAction
     *
     * @return mixed[]
     */
    public function departmentAction ()
    {

        $filterNamespace = $this->container->getParameter($this->getNamespace());
        $filters = $this->getFilters($filterNamespace);
        $this->clearPaginator($filterNamespace);

        $page = 1;
        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator = $this->get('knp_paginator');

        /** @var  $repository \Lists\DepartmentBundle\Entity\DepartmentsRepository */
        $repository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments');

        /** @var AccessService $accessService */
        $accessService = $this->get('access.service');

        $query = $repository->getFilteredDepartments($filters, $accessService->getAllowedDepartmentsId());

        $countDepartments = $repository
            ->getFilteredDepartments($filters, $accessService->getAllowedDepartmentsId(), "count")
            ->getSingleScalarResult();

        $query->setHint('knp_paginator.count', $countDepartments);
        $pagination = $paginator->paginate(
            $query,
            $page,
            20
        );

        return $this->render('ITDoorsOperBundle:Parts:department.html.twig', array (
                'pagination' => $pagination,
        ));
    }
    /**
     * departmentTableAction
     *
     * @return mixed[]
     */
    public function departmentTableAction ()
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace());
        $filters = $this->getFilters($filterNamespace);

        $page = $this->getPaginator($filterNamespace);
        if (!$page) {
            $page = 1;
        }

        /** @var  $departmentsRepository \Lists\DepartmentBundle\Entity\DepartmentsRepository */
        $departmentsRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments');

        /** @var AccessService $accessService */
        $accessService = $this->get('access.service');

        $query = $departmentsRepository->getFilteredDepartments($filters, $accessService->getAllowedDepartmentsId());

        $paginator = $this->get('knp_paginator');

        $countDepartments = $departmentsRepository
            ->getFilteredDepartments($filters, $accessService->getAllowedDepartmentsId(), "count")
            ->getSingleScalarResult();

        $query->setHint('knp_paginator.count', $countDepartments);

        $pagination = $paginator->paginate(
            $query,
            $page,
            20
        );

        return $this->render('ITDoorsOperBundle:Parts:department.html.twig', array (
                'pagination' => $pagination
        ));
    }

    public function exportDepartmentInfoAction() {
        ini_set("max_execution_time", "180");
        $transNamespace = 'ITDoorsOperBundle';
        /** @var  $departmentsRepository \Lists\DepartmentBundle\Entity\DepartmentsRepository */
        $departmentsRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments');

        $departments = $departmentsRepository->findAll();

        /** @var Translator $translator */
        $translator = $this->container->get('translator');
        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("Supervisor")
            ->setLastModifiedBy("Supervisor")
            ->setTitle("Departments")
            ->setSubject("Departments")
            ->setDescription("Departments")
            ->setKeywords("Departments")
            ->setCategory("Departments");
        $phpExcelObject->setActiveSheetIndex(0);


        $str = 1;
        $col = 0;

        $transHeader = $translator->trans('id', array(), $transNamespace);
        $phpExcelObject->getActiveSheet()
            ->setCellValueByColumnAndRow($col++, $str, $transHeader);
        $transHeader = $translator->trans('Mpk', array(), $transNamespace);
        $phpExcelObject->getActiveSheet()
            ->setCellValueByColumnAndRow($col++, $str, $transHeader);
        $transHeader = $translator->trans('Mpk old', array(), $transNamespace);
        $phpExcelObject->getActiveSheet()
            ->setCellValueByColumnAndRow($col++, $str, $transHeader);
        $transHeader = $translator->trans('Organization', array(), $transNamespace);
        $phpExcelObject->getActiveSheet()
            ->setCellValueByColumnAndRow($col++, $str, $transHeader);
        $transHeader = $translator->trans('Region', array(), $transNamespace);
        $phpExcelObject->getActiveSheet()
            ->setCellValueByColumnAndRow($col++, $str, $transHeader);
        $transHeader = $translator->trans('City', array(), $transNamespace);
        $phpExcelObject->getActiveSheet()
            ->setCellValueByColumnAndRow($col++, $str, $transHeader);
        $transHeader = $translator->trans('Address', array(), $transNamespace);
        $phpExcelObject->getActiveSheet()
            ->setCellValueByColumnAndRow($col++, $str, $transHeader);
        $transHeader = $translator->trans('Status', array(), $transNamespace);
        $phpExcelObject->getActiveSheet()
            ->setCellValueByColumnAndRow($col++, $str, $transHeader);
        $transHeader = $translator->trans('StatusDate', array(), $transNamespace);
        $phpExcelObject->getActiveSheet()
            ->setCellValueByColumnAndRow($col++, $str, $transHeader);
        $transHeader = $translator->trans('Type', array(), $transNamespace);
        $phpExcelObject->getActiveSheet()
            ->setCellValueByColumnAndRow($col++, $str, $transHeader);
        $transHeader = $translator->trans('Description', array(), $transNamespace);
        $phpExcelObject->getActiveSheet()
            ->setCellValueByColumnAndRow($col++, $str, $transHeader);
        $transHeader = $translator->trans('OperManager', array(), $transNamespace);
        $phpExcelObject->getActiveSheet()
            ->setCellValueByColumnAndRow($col++, $str, $transHeader);
        $transHeader = $translator->trans('Responsible', array(), $transNamespace);
        $phpExcelObject->getActiveSheet()
            ->setCellValueByColumnAndRow($col++, $str, $transHeader);

        /** @var  $department \Lists\DepartmentBundle\Entity\Departments */
        foreach ($departments as $department) {
            $col = 0;
            ++$str;

            $id = $department->getId();
            $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col++, $str, $id);

            $mpks = $department->getMpks();
            $content = '';
            /** @var  $newMpk \Lists\MpkBundle\Entity\Mpk */
            foreach ($mpks as $key => $newMpk) {
                $name = $newMpk->getName();
                $from = $newMpk->getStartDate();
                $to = $newMpk->getEndDate();
                $active = $newMpk->getActive();

                $selfOrganization = $newMpk->getOrganization();
                if ($selfOrganization) {
                    $selfOrganizationName = $selfOrganization->getShortname();
                } else {
                    $selfOrganizationName = $translator->trans('Not defined', array(), $transNamespace);
                }

                if (!$from) {
                    $from = $translator->trans('X', array(), $transNamespace);
                } else {
                    $from = $from->format('Y-m-d H:i:s');
                }

                if (!$to) {
                    $to = $translator->trans('X', array(), $transNamespace);
                } else {
                    $to = $to->format('Y-m-d H:i:s');
                }

                if ($active) {
                    $activeName = $translator->trans('Active', array(), $transNamespace);
                } else {
                    $activeName = $translator->trans('Non-active', array(), $transNamespace);
                }

                $content .=
                    $name.' ('.
                    $translator->trans('From', array(), $transNamespace).' '.$from.
                    ' - '.
                    $translator->trans('To', array(), $transNamespace).' '.$to
                    .')';

                $content .= '['.$selfOrganizationName.', '.$activeName.']';
                if ($newMpk != end($mpks)) {
                    $content .= ', ';
                }

            }
            $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col++, $str, $content);

            $mpk = $department->getMpk();
            $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col++, $str, $mpk);

            $organization = $department->getOrganization()->getName();
            $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col++, $str, $organization);

            $region = $department->getCity()->getRegion()->getName();
            $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col++, $str, $region);

            $city = $department->getCity()->getName();
            $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col++, $str, $city);

            $address = $department->getAddress();
            $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col++, $str, $address);

            $status = $department->getStatus();
            if ($status) {
                $statusName = $status->getName();
            } else {
                $statusName = '';
            }
            $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col++, $str, $statusName);

            $statusDate = $department->getStatusDate();
                if ($statusDate) {
                    $statusDateName = $statusDate->format('Y-m-d H:i:s');
                } else {
                    $statusDateName = '';
                }

            $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col++, $str, $statusDateName);

            $type = $department->getType();
            if ($type) {
                $typeName = $type->getName();
            } else {
                $typeName = '';
            }
            $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col++, $str, $typeName);

            $description = $department->getDescription();
            $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col++, $str, $description);

            $oper = $department->getOpermanager();
            if ($oper) {
                $operName = $oper->getFullname();
            } else {
                $operName = '';
            }
            $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col++, $str, $operName);

            $stuffDepartments = $this->getDoctrine()
                ->getRepository('SDUserBundle:StuffDepartments')
                ->findBy(array('departments' => $department));

            $content = '';

            foreach ($stuffDepartments as $key => $stuffDepartment) {
                $idStuff = $stuffDepartment->getStuff()->getId();
                $userkey = $stuffDepartment->getUserkey();

                $user= $stuffDepartment->getStuff()->getUser();
                $claim = $stuffDepartment->getClaimtypes()->getName();
                $content .= $user.' '.$userkey.' - '.$claim;

                if ($stuffDepartment != end($stuffDepartments)) {
                    $content .= ', '."\n";
                }

            }
            $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col++, $str, $content);

        }

        $phpExcelObject->getActiveSheet()
            ->getStyle('A1:AQ1')
            ->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //$phpExcelObject->getActiveSheet()->getStyle('A2:AQ'.$str)->getAlignment()->setWrapText(true);
        $phpExcelObject->getActiveSheet()->freezePane('AB2');
        $phpExcelObject->getActiveSheet()->setTitle('Departments');


        $fileName = 'Departments';
        $writer = $this->container->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->container->get('phpexcel')->createStreamedResponse($writer);
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=' . $fileName . '.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;

    }
}
