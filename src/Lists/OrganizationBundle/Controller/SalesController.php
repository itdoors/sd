<?php

namespace Lists\OrganizationBundle\Controller;

use Lists\OrganizationBundle\Entity\OrganizationServiceCoverRepository;
use Symfony\Component\HttpFoundation\Request;
use ITDoors\AjaxBundle\Controller\BaseFilterController as BaseController;
use PHPExcel_Style_Border;
use PHPExcel_Style_Alignment;
use Lists\OrganizationBundle\Entity\OrganizationUser;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SalesController
 */
class SalesController extends BaseController
{

    protected $filterNamespace = 'organization.sales.filters';
    protected $filterFormName = 'organizationSalesFilterForm';
    protected $baseRoute = 'lists_sales_organization_index';
    protected $baseRoutePrefix = 'sales';
    protected $baseTemplate = 'Sales';

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction ()
    {
        $namespase = $this->filterNamespace;
        $filter = $this->filterFormName;

        return $this->render('ListsOrganizationBundle:Sales:index.html.twig', array (
                'namespase' => $namespase,
                'filter' => $filter,
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction ()
    {
        $namespase = $this->filterNamespace;
        $filters = $this->getFilters($namespase);
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($namespase, $filters);
        }

        $page = $this->getPaginator($namespase);
        if (!$page) {
            $page = 1;
        }
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        /** @var \Doctrine\ORM\Query */
        $organizationsQuery = $organizationsRepository->getAllForSalesQuery($user->getId(), $filters);

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $organizationsQuery,
            $page,
            20
        );

        return $this->render('ListsOrganizationBundle:Sales:list.html.twig', array (
                'pagination' => $pagination,
                'namespase' => $namespase,
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }
    /**
     * Executes new action
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction (Request $request)
    {
        $form = $this->createForm('organizationSalesForm');

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var \Lists\OrganizationBundle\Entity\Organization $organization */
            $organization = $form->getData();

            $user = $this->getUser();

            $organization->setCreator($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($organization);
            $em->flush();

            $lookup = $this->getDoctrine()->getRepository('ListsLookupBundle:lookup')
                ->findOneBy(array ('lukey' => 'manager_organization'));
            $manager = new OrganizationUser();
            $manager->setOrganization($organization);
            $manager->setUser($user);
            $manager->setRole($lookup);
            $em->persist($manager);
            $em->flush();

            return $this->redirect($this->generateUrl('lists_' . $this->baseRoutePrefix . '_organization_show', array (
                        'id' => $organization->getId()
            )));
        }

        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate . ':new.html.twig', array (
                'filterForm' => $form->createView(),
                'filterFormName' => $this->filterFormName,
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }
    /**
     * Executes show action
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction ($id)
    {
        $this->get('sd.security_access')->hasAccessToOrganizationAndThrowException($id);

        /** @var \Lists\OrganizationBundle\Entity\Organization $organization */
        $organization = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($id);

        if ($organization->getParent()) {
            return $this->redirect($this->generateUrl('lists_' . $this->baseRoutePrefix . '_organization_show', array (
                        'id' => $organization->getParentId()
            )));
        }
        $lookups = $this->getDoctrine()
                ->getRepository('ListsLookupBundle:Lookup')->getGroupOrganizationQuery()->getQuery()->getResult();

        $managerForm = $this->createForm('organizationUserForm');

        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate . ':show.html.twig', array (
                'organization' => $organization,
                'lookups' => $lookups,
                'filterFormName' => $this->filterFormName,
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix,
                'managerForm' => $managerForm->createView()
        ));
    }
    /**
     * Renders organizationUsers list
     *
     * @param int $organizationId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function organizationUsersAction ($organizationId)
    {
        /** @var \Lists\OrganizationBundle\Entity\OrganizationUserRepository $organizationUser */
        $organizationUser = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:OrganizationUser');

        $organizationUsers = $organizationUser->getOrganizationUsersQuery($organizationId)
            ->getQuery()
            ->getResult();

        $lookup = $this->getDoctrine()->getRepository('ListsLookupBundle:lookup')
            ->findOneBy(array ('lukey' => 'manager_organization'));

        $managerOrganization = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:OrganizationUser')
            ->findOneBy(array (
            'organizationId' => $organizationId,
            'roleId' => $lookup->getId(),
            'userId' => $this->getUser()->getId(),
        ));

        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate . ':organizationUsers.html.twig', array (
                'organizationUsers' => $organizationUsers,
                'organizationId' => $organizationId,
                'managerOrganization' => $managerOrganization
        ));
    }
    /**
     * Renders organizationUsers list
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exportExcelAction ()
    {
        $namespase = $this->filterNamespace;
        $filters = $this->getFilters($namespase);
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($namespase, $filters);
        }

        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        /** @var \Doctrine\ORM\Query */
        $organizations = $organizationsRepository->getAllForSalesQuery($user->getId(), $filters)->getResult();

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
            ->setCellValue('J1', $translator->trans('Managers', array (), 'ListsOrganizationBundle'));
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
                    'lists_' . $this->baseRoutePrefix . '_organization_show',
                    array ('id' => $organization['organizationId']),
                    true
                ));
            $phpExcelObject->getActiveSheet()
                ->setCellValueByColumnAndRow(++$col, $str, $organization['organizationShortname'])
                ->setCellValueByColumnAndRow(++$col, $str, $organization['edrpou'])
                ->setCellValueByColumnAndRow(++$col, $str, $organization['viewName'])
                ->setCellValueByColumnAndRow(++$col, $str, $organization['cityName'])
                ->setCellValueByColumnAndRow(++$col, $str, $organization['regionName'])
                ->setCellValueByColumnAndRow(++$col, $str, $organization['scopeName'])
                ->setCellValueByColumnAndRow(++$col, $str, $organization['fullNames']);
        }
        $phpExcelObject->getActiveSheet()->getStyle('A2:J' . $str)->getAlignment()->setWrapText(true);
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

        $phpExcelObject->getActiveSheet()->getStyle('A1:J' . $str)->applyFromArray($styleArray);

        $phpExcelObject->getActiveSheet()
            ->getStyle('A2:A' . $str)
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcelObject->getActiveSheet()
            ->getStyle('A1:J1')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcelObject->getActiveSheet()
            ->getStyle('A1:J1')
            ->getAlignment()
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $phpExcelObject->getActiveSheet()
            ->getStyle('B2:J' . $str)
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcelObject->getActiveSheet()->freezePane('AB2');

        $phpExcelObject->getActiveSheet()->getStyle('A1:J' . $str)->getAlignment()->setWrapText(true);
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
