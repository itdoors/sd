<?php

namespace Lists\OrganizationBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Lists\OrganizationBundle\Entity\OrganizationUser;

/**
 * Class SalesAdminController
 */
class OrganizationController extends BaseController
{
    protected $filterNamespace = 'organization.filters';
    protected $filterFormName = 'organizationSalesFilterForm';
    /**
     * indexAction
     * 
     * @param string $type
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction ($type)
    {
        $service = $this->get('lists_organization.service');
        $access = $service->checkAccess($this->getUser());

        if (empty($type)) {
            $this->filterFormName = $access->filterFormName();
        }
        $namespase = $this->filterNamespace;

        return $this->render('ListsOrganizationBundle:Organization:index.html.twig', array (
                'namespase' => $namespase,
                'access' => $access,
                'type' => $type,
                'filter' => $this->filterFormName
        ));
    }
    /**
     * listAction
     * 
     * @param string $type
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction ($type)
    {
        $isShowUrl = true;
        $isShowProject = true;

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

        $userId = $user->getId();
        if (empty($type)) {
            $service = $this->get('lists_organization.service');
            $access = $service->checkAccess($user);
            if ($access->canSeeAll()) {
                $userId = null;
            } else {
                throw new \Exception('No access', 403);
            }
        }
        /** @var \Doctrine\ORM\Query */
        $organizationsQuery = $organizationsRepository->getAllForSalesQuery($userId, $filters);
        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $organizationsQuery,
            $page,
            20
        );

        return $this->render('ListsOrganizationBundle:Organization:list.html.twig', array (
                'pagination' => $pagination,
                'namespase' => $namespase,
                'isShowUrl' => $isShowUrl,
                'isShowProject' => $isShowProject
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
        $user = $this->getUser();

        $service = $this->get('lists_organization.service');
        $access = $service->checkAccess($user);

        if (!$access->canAddOrganization()) {
            throw new \Exception('No access');
        }

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

            return $this->redirect($this->generateUrl('lists_organization_show', array (
                'id' => $organization->getId()
            )));
        }

        return $this->render('ListsOrganizationBundle:Organization:new.html.twig', array (
                'filterForm' => $form->createView(),
                'filterFormName' => $this->filterFormName
        ));
    }
    /**
     * Saves object to db
     *
     * @return mixed[]
     */
    public function ajaxSaveAction()
    {
        $translator = $this->get('translator');

        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value');

        $methodSet = 'set' . ucfirst($name);

        $organization = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($pk);
        if ($name == 'organizationsign') {
            $methodSet = 'add' . ucfirst($name);
            $lookups = $organization->getOrganizationsigns();
            foreach ($lookups as $lookup) {
                $organization->removeOrganizationsign($lookup);
            }
            foreach ($value as $val) {
                $lookups = $this->getDoctrine()
                ->getRepository('ListsLookupBundle:Lookup')->find($val);
                $organization->$methodSet($lookups);
            }
        } else {

            if (!$value) {
                $methodGet = 'get' . ucfirst($name);
                $type = gettype($organization->$methodGet());

                if (in_array($type, array('integer'))) {
                    $value = null;
                }
            }

            $organization->$methodSet(trim($value));
        }
        $validator = $this->get('validator');
        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors */
        $errors = $validator->validate($organization, array('edit'));

        if (sizeof($errors)) {
            $return = $this->getFirstError($errors);

            return new Response($return, 406);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($organization);

        try {
            $em->flush();
        } catch (\ErrorException $e) {
            $return = array('msg' => $translator->trans('Wrong input data'));

            return new Response(json_encode($return));
        }

        $return = array('success' => 1);

        return new Response(json_encode($return));
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
        /** @var \Lists\OrganizationBundle\Entity\Organization $organization */
        $organization = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($id);
        if (!$organization) {
            return $this->render('ListsOrganizationBundle:Organization:notFound.html.twig');
        }

        $service = $this->get('lists_organization.service');
        $access = $service->checkAccess($this->getUser(), $organization);
        
        if (!$access->canSee()) {
            return $this->render('ListsOrganizationBundle:Organization:noAccess.html.twig', array(
                'organization' => $organization
            ));
        }

        if ($organization->getParent()) {
            return $this->redirect($this->generateUrl('lists_organization_show', array (
                'id' => $organization->getParentId()
            )));
        }
        $lookups = $this->getDoctrine()
                ->getRepository('ListsLookupBundle:Lookup')->getGroupOrganizationQuery()->getQuery()->getResult();

        $managerForm = $this->createForm('organizationUserForm');

        return $this->render('ListsOrganizationBundle:Organization:show.html.twig', array (
                'organization' => $organization,
                'lookups' => $lookups,
                'filterFormName' => $this->filterFormName,
                'managerForm' => $managerForm->createView(),
                'access' => $access
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

        return $this->render('ListsOrganizationBundle:Organization:organizationUsers.html.twig', array (
                'organizationUsers' => $organizationUsers,
                'organizationId' => $organizationId,
                'managerOrganization' => $managerOrganization
        ));
    }
    /**
     * Renders organization list
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function organizationTransferAction()
    {
        $namespase = $this->filterNamespace.'.transfer';

        return $this->render('ListsOrganizationBundle:Organization:organizationTransfer.html.twig', array(
                'namespase' => $namespase,
        ));
    }
    /**
     * Renders organization list
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function organizationForUserAction()
    {
        $namespase = $this->filterNamespace.'.transfer';
        $filters = $this->getFilters($namespase);

        $em = $this->getDoctrine()->getManager();
        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $em
            ->getRepository('ListsOrganizationBundle:Organization');

        $departmensQuery = array();
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($namespase, $filters);
            $organizationsQuery = array();
            $departmensQuery = array();
        } else {
            /** @var \Doctrine\ORM\Query */
            $organizationsQuery = $organizationsRepository->getAllForManagerQuery(null, $filters)->getResult();
            if ($this->getUser()->hasRole('ROLE_HRADMIN')) {
                $userId = $filters['user'];

                /** @var \SD\UserBundle\Entity\User $user */
                $user = $em->getRepository('SDUserBundle:User')->find($userId);

                $stuffDepartmensQuery = $user->getStuff()->getStuffDepartments(array(''));
                $organizations = array();
                foreach ($stuffDepartmensQuery as $stuff) {
                    $departments = $stuff->getDepartments();
                    $organizations[$departments->getOrganizationId()][$departments->getId()] = $departments;
                }
                /* order by organization */
                foreach ($organizations as $organization) {
                    foreach ($organization as $key => $dep) {
                        $departmensQuery[$key] = $dep;
                    }
                }
            } else {
                $departmensQuery = array();
            }
        }

        return $this->render('ListsOrganizationBundle:Organization:organizationForUser.html.twig', array(
            'organizations' => $organizationsQuery,
            'departmens' => $departmensQuery
        ));
    }
    /**
     * Renders organization list
     * 
     * @param Request $request
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function organizationTransferForUserAction(Request $request)
    {
        $namespase = $this->filterNamespace.'.transfer';
        $filters = $this->getFilters($namespase);

        $userIdOld = $filters['user'];
        $userId = $request->get('userId');
        $organizationIds = $request->get('organizations');
        $departmensIds = $request->get('departmens');

        if (empty($userIdOld) || empty($userId) || (empty($organizationIds) && empty($departmensIds))) {
            return new Response(json_encode(array('error' => 'error data')));
        }
        $em = $this->getDoctrine()->getManager();
        /** @var \SD\UserBundle\Entity\User $u */
        $u = $this->getDoctrine()->getRepository('SDUserBundle:User')->find($userId);

        if (!empty($organizationIds)) {
            /** @var \Lists\OrganizationBundle\Entity\OrganizationUserRepository $oU */
            $oU = $this->getDoctrine()
                ->getRepository('ListsOrganizationBundle:OrganizationUser');

            $lookup = $this->getDoctrine()->getRepository('ListsLookupBundle:lookup')
                ->findOneBy(array('lukey' => 'manager_organization'));

            foreach ($organizationIds as $organizationId) {
                $organizationUser = $oU->findOneBy(array(
                    'organizationId' => $organizationId,
                    'userId' => $userIdOld
                        ));
                if (!$organizationUser) {
                    $organization = $this->getDoctrine()
                        ->getRepository('ListsOrganizationBundle:Organization')->find($organizationId);
                    $organizationUser = new OrganizationUser();
                    $organizationUser->setLookup($lookup);
                    $organizationUser->setOrganization($organization);
                }
                $organizationUser->setUser($u);
                $em->persist($organizationUser);

                $serviceHandlingUser = $this->container->get('lists_handling.user.service');
                $serviceHandlingUser->changeManagerProject($organizationId, $userId);
            }
        }
        if ($this->getUser()->hasRole('ROLE_HRADMIN') && !empty($departmensIds)) {
            $userOld = $em
                    ->getRepository('SDUserBundle:User')
                    ->find($userIdOld);
            if (!$userOld) {
                throw new Exception('User not found', 404);
            }
            $stuffOld = $userOld->getStuff();
            $stuff = $u->getStuff();
            foreach ($departmensIds as $departmenId) {
                $department = $em
                    ->getRepository('ListsDepartmentBundle:Departments')
                    ->find($departmenId);
                $stuffDepartments = $em
                    ->getRepository('SDUserBundle:StuffDepartments')
                    ->findBy(array (
                        'stuff' => $stuffOld,
                        'departments' => $department
                    ));
                foreach ($stuffDepartments as $stuffDepartment) {
                    $stuffDepartmentOld = $em
                    ->getRepository('SDUserBundle:StuffDepartments')
                    ->findBy(array (
                        'stuff' => $stuffOld,
                        'departments' => $department,
                        'claimtypes' => $stuffDepartment->getClaimtypes(),
                    ));
                    if ($stuffDepartmentOld) {
                        $em->remove($stuffDepartment);
                    } else {
                        $stuffDepartment->setStuff($stuff);
                        $em->persist($stuffDepartment);
                    }
                }
            }
        }
        $em->flush();

        $result = array('success' => true);

        return new Response(json_encode($result));
    }
    /**
     * Renders departments
     * 
     * @param integer $id Organization.id
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function departmentsAction($id)
    {
        $isAddDepartment = false;
        if ($this->getUser()->hasRole('ROLE_DOGOVORADMIN')) {
            $isAddDepartment = true;
        }

        return $this->render('ListsOrganizationBundle:Organization:departments.html.twig', array(
            'organizationId' => $id,
            'isAddDepartment' => $isAddDepartment
        ));
    }
    /**
     * Renders departments list
     * 
     * @param integer $id Organization.id
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function departmentsListAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $organization = $em->getRepository('ListsOrganizationBundle:Organization')->find($id);
        if (!$organization) {
            throw new Exception('Organization not found', 404);
        }
        $departments = $organization->getDepartments();

        return $this->render('ListsDepartmentBundle:Department:departmentsListDataTable.html.twig', array(
            'departments' => $departments
        ));
    }
    /**
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function listKvedAction($id)
    {
        $kvedOrganizationRepo = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:KvedOrganization');

        $organization = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($id);

        $kvedOrganizations = $kvedOrganizationRepo->findBy(array(
            'organization' => $organization
        ));

        $kveds = array();

        foreach ($kvedOrganizations as $kvedOrganization) {
            $kved = $kvedOrganization->getKved();
            $kveds[] = $kved;
        }

        return $this->render('ListsOrganizationBundle:Organization:listKved.html.twig', array(
            'kveds' => $kveds,
            'organizationId' => $id
        ));
    }
    /**
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listDocumentAction($id)
    {
        /** @var DogovorRepository $dr */
        $documentsOrganizationRepo = $this->getDoctrine()
            ->getRepository('ListsDocumentBundle:DocumentsOrganization');

        $organization = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($id);

        $documentsOrganization = $documentsOrganizationRepo->findBy(array(
            'organization' => $organization
        ));

        $docs = array();

        foreach ($documentsOrganization as $documentOrganization) {
            $doc = $documentOrganization->getDocuments();
            $docs[] = $doc;
        }

        return $this->render('ListsOrganizationBundle:Organization:listDocument.html.twig', array(
            'documents' => $docs,
            'organizationId' => $id
        ));
    }
    /**
     * Renders organizationUsers list
     * 
     * @param string $type
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exportExcelAction ($type)
    {
        $namespase = $this->filterNamespace;
        $filters = $this->getFilters($namespase);
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($namespase, $filters);
        }

        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $service = $this->get('lists_organization.service');
        $access = $service->checkAccess($this->getUser());

        if ($access->canExportToExcel()) {
            throw new \Exception('No access', 403);
        }

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        $userId = $user->getId();
        if (empty($type)) {
            if ($access->canSeeAll()) {
                $userId = null;
            } else {
                throw new \Exception('No access', 403);
            }
        }
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
                    'lists_' . $this->baseRoutePrefix . '_organization_show',
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
