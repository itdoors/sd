<?php

namespace Lists\OrganizationBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Lists\OrganizationBundle\Entity\OrganizationUser;
use PHPExcel_Style_Border;
use PHPExcel_Style_Alignment;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Lists\OrganizationBundle\Form\OrganizationCreateForm;

/**
 * Class SalesAdminController
 */
class ContractorController extends OrganizationController
{
    protected $filterNamespace = 'organization.filters';
    protected $filterFormName = 'contractorSalesFilterForm';

    /**
     * indexAction
     * 
     * @param string $type
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction ($type = null)
    {
        $service = $this->get('lists_organization.service');
        $access = $service->checkAccess($this->getUser());

        if (empty($type)) {
            $this->filterFormName = $access->filterFormName();
        }
        $namespase = $this->filterNamespace;

        return $this->render('ListsOrganizationBundle:Contractor:index.html.twig', array (
                'namespase' => $namespase,
                'access' => $access,
                'type' => '',
                'filter' => 'contractorSalesFilterForm'
        ));
    }
    /**
     * listAction
     * 
     * @param string $type
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction ($type = null)
    {
        $isShowUrl = true;
        $isShowProject = true;

        $namespase = $this->filterNamespace;
        $filters = $this->getFilters($namespase);
        /*if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($namespase, $filters);
        }*/
        //$filters['organization'] = 'No fired';
        $filters['organizationsigns'] = 65;

        $page = $this->getPaginator($namespase);
        if (!$page) {
            $page = 1;
        }
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        $userId = null;

        /** @var \Doctrine\ORM\Query */
        $organizationsQuery = $organizationsRepository->getAllForSalesQuery($userId, $filters);
        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $organizationsQuery,
            $page,
            20
        );

        return $this->render('ListsOrganizationBundle:Contractor:list.html.twig', array (
                'pagination' => $pagination,
                'namespase' => $namespase,
                'isShowUrl' => $isShowUrl,
                'isShowProject' => $isShowProject
        ));
    }

    /**
     * Renders organizationUsers list
     *
     * @param string $type
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exportExcelAction ($type = null)
    {
        $namespase = $this->filterNamespace;
        $filters = $this->getFilters($namespase);
        /*if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($namespase, $filters);
        }*/
        $filters['organizationsigns'] = 65;
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $service = $this->get('lists_organization.service');
        $access = $service->checkAccess($this->getUser());

        /*if (!$access->canExportToExcel()) {
            throw new \Exception('No access', 403);
        }*/

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        $userId = $user->getId();
/*        if (empty($type)) {
            if ($access->canSeeAll()) {*/
                $userId = null;
/*            } else {
                throw new \Exception('No access', 403);
            }*/
        //}
        /** @var \Doctrine\ORM\Query */
        $organizations = $organizationsRepository->getAllForSalesQuery($userId, $filters)->getResult();

        $response = $this->exportToExcelAction($organizations);

        return $response;
    }
    /**
     * Renders organizationUsers list
     *
     * @param array $organizations
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function exportToExcelAction ($organizations)
    {
        /** @var Translator $translator */
        $translator = $this->container->get('translator');

        // ask the service for a Excel5
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("DebtControll")
            ->setLastModifiedBy("Giulio De Donato")
            ->setTitle("Organization")
            ->setSubject("Organization")
            ->setDescription("Organizations list")
            ->setKeywords("Organization")
            ->setCategory("Organization");
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', $translator->trans('ID', array (), 'ListsOrganizationBundle'))
            ->setCellValue('B1', $translator->trans('Ownership', array (), 'ListsOrganizationBundle'))
            ->setCellValue('C1', $translator->trans('Name', array (), 'ListsOrganizationBundle'))
            ->setCellValue('D1', $translator->trans('Short Name', array (), 'ListsOrganizationBundle'))
            ->setCellValue('E1', $translator->trans('Edrpou', array (), 'ListsOrganizationBundle'))
            ->setCellValue('F1', $translator->trans('View', array (), 'ListsOrganizationBundle'))
            ->setCellValue('G1', $translator->trans('City', array (), 'ListsOrganizationBundle'))
            ->setCellValue('H1', $translator->trans('Region', array (), 'ListsOrganizationBundle'))
            ->setCellValue('I1', $translator->trans('Scope', array (), 'ListsOrganizationBundle'))
            ->setCellValue('J1', $translator->trans('Managers', array (), 'ListsOrganizationBundle'))
            ->setCellValue('K1', $translator->trans('Kveds', array (), 'ListsOrganizationBundle'));
        $phpExcelObject->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
        $str = 1;

        foreach ($organizations as $organization) {
            ++$str;
            $col = 0;
            $phpExcelObject->getActiveSheet()
                ->setCellValueByColumnAndRow($col, $str, $organization['organizationId'])
                ->setCellValueByColumnAndRow(++$col, $str, $organization['ownershipShortname'])
                ->setCellValueByColumnAndRow(++$col, $str, $organization['organizationName']);
            $phpExcelObject->getActiveSheet()->getCellByColumnAndRow($col, $str)->getHyperlink()
                ->setUrl($this->generateUrl(
                    'lists_organization_show',
                    array ('id' => $organization['organizationId']),
                    true
                ));
            $phpExcelObject->getActiveSheet()
                ->setCellValueByColumnAndRow(++$col, $str, $organization['organizationShortname'])
                ->setCellValueByColumnAndRow(++$col, $str, $organization['edrpou'])
                ->setCellValueByColumnAndRow(++$col, $str, $organization['viewNames'])
                ->setCellValueByColumnAndRow(++$col, $str, $organization['cityName'])
                ->setCellValueByColumnAndRow(++$col, $str, $organization['regionName'])
                ->setCellValueByColumnAndRow(++$col, $str, $organization['scopeName'])
                ->setCellValueByColumnAndRow(++$col, $str, $organization['fullNames'])
                ->setCellValueByColumnAndRow(++$col, $str, $organization['kveds']);
        }
        $phpExcelObject->getActiveSheet()->getStyle('A2:K' . $str)->getAlignment()->setWrapText(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth(13);
        $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $phpExcelObject->getActiveSheet()->getColumnDimension('E')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('G')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('H')->setWidth(13);
        $phpExcelObject->getActiveSheet()->getColumnDimension('I')->setWidth(13);
        $phpExcelObject->getActiveSheet()->getColumnDimension('J')->setWidth(13);
        $phpExcelObject->getActiveSheet()->getColumnDimension('K')->setWidth(13);

        $styleArray = array (
            'borders' => array (
                'outline' => array (
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
                    'color' => array ('argb' => '000000'),
                ),
                'inside' => array (
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array ('argb' => '000000'),
                )
            ),
        );

        $phpExcelObject->getActiveSheet()->getStyle('A1:K' . $str)->applyFromArray($styleArray);

        $phpExcelObject->getActiveSheet()
            ->getStyle('A2:A' . $str)
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcelObject->getActiveSheet()
            ->getStyle('A1:K1')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcelObject->getActiveSheet()
            ->getStyle('A1:K1')
            ->getAlignment()
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $phpExcelObject->getActiveSheet()
            ->getStyle('B2:K' . $str)
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcelObject->getActiveSheet()->freezePane('AB2');

        $phpExcelObject->getActiveSheet()->getStyle('A1:K' . $str)->getAlignment()->setWrapText(true);
        $phpExcelObject->getActiveSheet()->setShowGridLines(false); //off line
        $phpExcelObject->getActiveSheet()->setTitle('Organization');
        $phpExcelObject->setActiveSheetIndex(0);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=organizations.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }
}
