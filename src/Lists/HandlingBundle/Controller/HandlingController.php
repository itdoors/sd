<?php

namespace Lists\HandlingBundle\Controller;

use ITDoors\CommonBundle\Controller\BaseFilterController as BaseController;
use Lists\HandlingBundle\Entity\HandlingMessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Lists\HandlingBundle\Entity\HandlingUser;
use Lists\HandlingBundle\Entity\HandlingResult;
use Lists\ContactBundle\Entity\ModelContactRepository;
use Lists\HandlingBundle\Entity\Handling;
use Lists\HandlingBundle\Entity\HandlingMessage;

/**
 * Class HandlingController
 */
class HandlingController extends BaseController
{
    protected $filterNamespace = 'handling.sales.filters';
    protected $filterFormName = 'handlingSalesFilterForm';
    protected $wizardOrganizationNamespace = 'sales.wizard.organization';
    protected $wizardHandlingNamespace = 'sales.wizard.handling';
    /**
     * indexAction
     * 
     * @param string $type
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($type)
    {
        $filterNamespace = $this->filterNamespace;
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $service = $this->get('lists_handling.service');
        $access = $service->checkAccess($user);

        if (!$access->canSeeList() && empty($type) && !$access->canSeeListMy()) {
            return $this->render('ListsHandlingBundle:Handling:noAccess.html.twig');
        }

        return $this->render('ListsHandlingBundle:Handling:index.html.twig', array(
                'filter' => $this->filterFormName,
                'access' => $access,
                'type' => $type,
                'filterNamespace' => $filterNamespace
            ));
    }
    /**
     * indexAction
     * 
     * @param string $type
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($type)
    {
        $filterNamespace = $this->filterNamespace;
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $service = $this->get('lists_handling.service');
        $access = $service->checkAccess($user);

        if (!$access->canSeeList() && empty($type) && !$access->canSeeListMy()) {
            return $this->render('ListsHandlingBundle:Handling:noAccess.html.twig');
        }
        $baseFilter = $this->container->get('it_doors_ajax.base_filter_service');
        $filters = $baseFilter->getFilters($filterNamespace);
        
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($filterNamespace, $filters);
        }

        $page = $baseFilter->getPaginator($filterNamespace);
        if (!$page) {
            $page = 1;
        }
            
        /** @var \Lists\HandlingBundle\Entity\HandlingRepository $handlingRepository */
        $handlingRepository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling');

        $userId = null;
        if ($type == 'my') {
            $userId = $user->getId();
        }

        /** @var \Doctrine\ORM\Query $handlingQuery */
        $handlingQuery = $handlingRepository->getAllForSalesQuery($userId, $filters);

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $handlingQuery,
            $page,
            20
        );

        $canAddNew = $this->getFilterValueByKey('organization') ? true : false;
        if ($canAddNew) {
            /** @var \Lists\OrganizationBundle\Entity\OrganizationUserRepository $manager */
            $manager = $this->getDoctrine()
                ->getRepository('ListsOrganizationBundle:OrganizationUser')
                ->findOneBy(array(
                    'organizationId' => $this->getFilterValueByKey('organization'),
                    'userId' => $this->getUser()->getId()
                ));
            if (!$manager) {
                $canAddNew = false;
            }
        }
        
//        $baseFilter = $this->container->get('it_doors_ajax.base_filter_service');

        return $this->render('ListsHandlingBundle:Handling:list.html.twig', array(
                'filterNamespace' => $filterNamespace,
                'pagination' => $pagination,
                'canAddNew' => $canAddNew,
                'access' => $access,
                'type' => $type
            ));
    }
    /**
     * Executes show action
     *
     * @param int     $id
     * @param string  $type
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction ($id, $type)
    {
        /** @var BaseService $baseService */
        $baseService = $this->get('itdoors_common.base.service');

        /** @var HandlingRepository $handlingRepository */
        $handlingRepository = $this->get('handling.repository');

        /** @var \Lists\HandlingBundle\Entity\Handling $object */
        $object = $handlingRepository->getHandlingShow($id);
        /** @var \Lists\HandlingBundle\Entity\Handling $handling */
        $handling = $handlingRepository->find($id);
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $service = $this->get('lists_handling.service');
        $access = $service->checkAccess($user, $handling);

        if (!$access->canSee()) {
            return $this->render('ListsHandlingBundle:Handling:noAccess.html.twig');
        }

        $object['isMarketingChoices'] = $baseService->getYesNoChoices();

        /** @var \Lists\OrganizationBundle\Entity\Organization $organization */
        $organization = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($object[0]->getOrganizationId());

        $handlingServiceObjects = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingService')
            ->findAll();

        $handlingServices = array ();

        foreach ($handlingServiceObjects as $hs) {
            $handlingServices[] = $this->serializeObject($hs);
        }

        $canEdit = (Boolean) !$object[0]->getIsClosed();

        $isResultClosed =
            $object['resultSlug'] == HandlingResult::RESULT_CLOSED
            ||
            $object['resultSlug'] == HandlingResult::RESULT_COMPETITOR;

        $showMoreInfoIds = array (5, 6);

        $lookups = $this->getDoctrine()
                ->getRepository('ListsLookupBundle:Lookup')->getGroupOrganizationQuery()->getQuery()->getResult();

        $serviceOrganization = $this->get('lists_organization.service');
        $accessOrganization = $serviceOrganization->checkAccess($user, $organization);

        return $this->render('ListsHandlingBundle:Handling:show.html.twig', array (
                'handling' => $object,
                'handlingServices' => $handlingServices,
                'canEdit' => $canEdit,
                'isResultClosed' => $isResultClosed,
                'organization' => $organization,
                'showMoreInfoIds' => $showMoreInfoIds,
                'lookups' => $lookups,
                'accessOrganization' => $accessOrganization,
                'type' => $type,
                'access' => $access
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
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        // Get organization filter
        $filters = $this->getFilters();

        $organizationId = (int) $filters['organization_id'];

        if (!isset($filters['organization_id']) || !$filters['organization_id']) {
            return $this->redirect($this->generateUrl('lists_organization_index'));
        }

        $organization = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($organizationId);

        if (!$organization) {
            throw new \Excepton('Organization don`t found', 403);
        }

        $service = $this->get('lists_organization.service');
        $access = $service->checkAccess($this->getUser(), $organization);

        if (!$access->canAddHandling()) {
            return $this->render('ListsHandlingBundle:Handling:noAccess.html.twig');
        }

        $form = $this->createForm('handlingSalesForm');

        $form
            ->add('organization', 'text', array (
                'disabled' => true,
                'data' => (string) $organization
            ))
            ->add('user', 'text', array (
                'disabled' => true,
                'data' => (string) $user
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var \Lists\HandlingBundle\Entity\Handling $object */
            $object = $form->getData();

            $object->setUser($user);
            $object->setCreatedatetime(new \DateTime());
            $object->setOrganization($organization);

            $lookup = $this->getDoctrine()->getRepository('ListsLookupBundle:lookup')
                ->findOneBy(array ('lukey' => 'manager_project'));
            $manager = new HandlingUser();
            $manager->setUser($user);
            $manager->setLookup($lookup);
            $manager->setPart(100);
            $manager->setHandling($object);

            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->persist($manager);
            $em->flush();

            return $this->redirect($this->generateUrl('lists_handling_show', array (
                        'id' => $object->getId(),
                        'type' => 'my',
            )));
        }

        return $this->render('ListsHandlingBundle:Handling:new.html.twig', array (
                'form' => $form->createView(),
                'filterFormName' => $this->filterFormName
        ));
    }
    /**
     * Renders handlingUsers list
     *
     * @param int $handlingId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handlingUsersAction ($handlingId)
    {
        /** @var \Lists\HandlingBundle\Entity\HandlingUserRepository $handlingUser */
        $handlingUser = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingUser');

        $handlingUsers = $handlingUser->getHandlingUsersQuery($handlingId)
            ->getQuery()->getResult();

        /** @var HandlingRepository $handlingRepository */
        $handlingRepository = $this->get('handling.repository');

        /** @var \Lists\HandlingBundle\Entity\Handling $handling */
        $handling = $handlingRepository->find($handlingId);

        $service = $this->get('lists_handling.service');
        $access = $service->checkAccess($this->getUser(), $handling);

        return $this->render('ListsHandlingBundle:Handling:handlingUsers.html.twig', array (
                'handlingUsers' => $handlingUsers,
                'handlingId' => $handlingId,
                'access' => $access
        ));
    }
     /**
     * @param int $handlingId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function messagesListAction ($handlingId)
    {
        /** @var \Lists\HandlingBundle\Entity\HandlingMessageRepository $messagesRepository */
        $messagesRepository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingMessage');

        $messages = $messagesRepository->getMessagesByHandlingId($handlingId);

        $usersFromTheirSide = array ();
        $usersFromOurSide = array ();
        $calls = array ();
        foreach ($messages as $message) {
            $usersFromTheirSideTemp = $this->getDoctrine()
                ->getRepository('ListsHandlingBundle:HandlingMessageModelContact')
                ->findBy(array (
                'handlingMessage' => $message
            ));

            $usersFromTheirSide['message' . $message->getId()] = $usersFromTheirSideTemp;

            $usersFromOurSideTemp = $this->getDoctrine()
                ->getRepository('ListsHandlingBundle:HandlingMessageHandlingUser')
                ->findBy(array (
                'handlingMessage' => $message
            ));

            $usersFromOurSide['message' . $message->getId()] = $usersFromOurSideTemp;

            if ($message->getType() && $message->getType()->getId() === 1) {
                $call = $this->getDoctrine()
                    ->getRepository('ITDoorsSipBundle:Call')
                    ->findOneBy(array('modelName' => 'handling_message' ,'modelId' => $message->getId()));
                if ($call) {
                    $calls[$message->getId()] = $call;
                }
            }
        }

        return $this->render('ListsHandlingBundle:Handling:messagesList.html.twig', array (
                'messages' => $messages,
                'usersFromTheirSide' => $usersFromTheirSide,
                'usersFromOurSide' => $usersFromOurSide,
                'calls' => $calls
        ));
    }
    /**
     * Execute addOrganizationFilter action
     *
     * @param int $organizationId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addOrganizationFilterAction ($organizationId)
    {
        $filters = $this->getFilters();

        $filters['organization_id'] = $organizationId;

        $this->setFilters($filters);

        return $this->redirect($this->generateUrl('lists_handling_index'));
    }

    /**
     * indexServicesAction
     */
    public function indexServicesAction()
    {
        /** @var \Lists\HandlingBundle\Entity\HandlingRepository $handlingRepository */
        $handlingServices = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingService')
            ->findAll();

    }

    /**
     * Executes close action
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function closeAction($id)
    {
        if (!$id) {
            return $this->redirect($this->generateUrl('lists_handling_handling_index'));
        }

        $user = $this->getUser();

        if (!$user->hasRole('ROLE_SALESADMIN')) {
            throw new AccessDeniedException();
        }

        /** @var \Lists\HandlingBundle\Entity\Handling $handling */
        $handling = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling')
            ->find($id);

        $value = (Boolean) $handling->getIsClosed();

        $handling->setIsClosed(!$value);
        $handling->setClosedatetime(new \DateTime());
        $handling->setCloser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($handling);
        $em->flush();

        return $this->redirect($this->generateUrl('lists_' . $this->baseRoutePrefix . '_handling_show', array(
                'id' => $id
            )));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reportSimpleAction()
    {
        /** @var \Lists\HandlingBundle\Services\HandlingService $service */
        $service = $this->get('lists_handling.service');
        $access = $service->checkAccess($this->getUser());

        if (!$access->canSeeReport()) {
            return $this->render('ListsHandlingBundle:Handling:noAccess.html.twig');
        }

        /** @var \Lists\HandlingBundle\Entity\HandlingRepository $handlingRepository */
        $handlingRepository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling');

        /** @var \Doctrine\ORM\Query $handlingQuery */
        $handlingQuery = $handlingRepository->getAllForSalesQuery(null, array());

        $results = $handlingQuery->getResult();

        return $this->render('ListsHandlingBundle:Handling:reportSimple.html.twig', array(
                'results' => $results
            ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reportAdvancedRangeAction()
    {
        /** @var \Lists\HandlingBundle\Services\HandlingService $service */
        $service = $this->get('lists_handling.service');
        $access = $service->checkAccess($this->getUser());

        if (!$access->canSeeReport()) {
            return $this->render('ListsHandlingBundle:Handling:noAccess.html.twig');
        }

        $form = $this->createForm('handlingReportDateRangeForm');

        return $this->render('ListsHandlingBundle:Handling:reportAdvancedRange.html.twig', array(
                'form' => $form->createView()
            ));
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function reportAdvancedDoneAction(Request $request)
    {
        /** @var \Lists\HandlingBundle\Services\HandlingService $service */
        $service = $this->get('lists_handling.service');
        $access = $service->checkAccess($this->getUser());

        if (!$access->canSeeReport()) {
            return $this->render('ListsHandlingBundle:Handling:noAccess.html.twig');
        }

        $form = $this->createForm('handlingReportDateRangeForm');

        $data = $request->request->get($form->getName());

        if (!sizeof($data)) {
            return $this->redirect($this->generateUrl('lists_handling_report_advanced_range'));
        }

        $from = new \DateTime($data['from']);
        $to = new \DateTime('23:59:59 '.$data['to']);
        $managers = null;
        if (isset($data['manager'])) {
            $managers = $data['manager'];
        }

        /** @var \Lists\HandlingBundle\Entity\HandlingMessageRepository $handlingRepository */
        $handlingMessageRepository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingMessage');

        /** @var HandlingMessageRepository $handlingMessageRepository */
        $results = $handlingMessageRepository->getAdvancedResult($from, $to, $managers);

        $types = $this->getDoctrine()->getRepository('ListsHandlingBundle:HandlingMessageType')
            ->getList();

        return $this->render('ListsHandlingBundle:Handling:reportAdvancedDone.html.twig', array(
                'results' => $results,
                'types' => $types,
                'from' => $from,
                'to' => $to
            ));

    }

    /**
     * Executes list action for dashboard
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
//    public function listAction()
//    {
//        // Get organization filter
//        /** @var \Lists\HandlingBundle\Entity\HandlingRepository $handlingRepository */
//        $handlingRepository = $this->getDoctrine()
//            ->getRepository('ListsHandlingBundle:Handling');
//
//        $filters['progressNOT'] = 100;
//        $filters['chanceNOT'] = array(0, 100);
//        $filters['isClosed'] = 'FALSE';
//
//        /** @var \Doctrine\ORM\Query $handlingQuery */
//        $handlingQuery = $handlingRepository->getAllForSalesQuery(null, $filters);
//
//        $pagination = $handlingQuery->getResult();
//
//        /** @var \Knp\Component\Pager\Paginator $paginator */
//
//        return $this->render('ListsHandlingBundle:' . $this->baseTemplate . ':list.html.twig', array(
//                'pagination' => $pagination,
//                'baseRoutePrefix' => $this->baseRoutePrefix,
//                'baseTemplate' => $this->baseTemplate,
//            ));
//    }
     /**
     * Renders organizationUsers list
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exportExcelAction()
    {
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $service = $this->get('lists_handling.service');
        $access = $service->checkAccess($user);

        $userId = $user->getId();
        if ($access->canExportToExelAll) {
            $userId = null;
        }
         // Get organization filter
        $filters = $this->getFilters();

        /** @var HandlingRepository $handlingRepository */
        $handlingRepository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling');

        /** @var \Doctrine\ORM\Query $handlingQuery */
        $handlingQuery = $handlingRepository->getAllForExport($userId, $filters);

        $response = $this->exportToExcelAction($handlingQuery);

        return $response;
    }
    /**
     * Renders organizationUsers list
     *
     * @param array $handlings
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function exportToExcelAction ($handlings)
    {
        /** @var Translator $translator */
        $translator = $this->container->get('translator');

        // ask the service for a Excel5
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("DebtControll")
            ->setLastModifiedBy("Giulio De Donato")
            ->setTitle("Handling")
            ->setSubject("Handling")
            ->setDescription("Handling list")
            ->setKeywords("Handling")
            ->setCategory("Handling");
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', $translator->trans('Managers', array (), 'ListsHandlingBundle'))
            ->setCellValue('B1', $translator->trans('ID', array (), 'ListsHandlingBundle'))
            ->setCellValue('C1', $translator->trans('Name', array (), 'ListsHandlingBundle'))
            ->setCellValue('D1', $translator->trans('Createdatetime', array (), 'ListsHandlingBundle'))
            ->setCellValue('E1', $translator->trans('LastHandlingDate', array (), 'ListsHandlingBundle'))
            ->setCellValue('F1', $translator->trans('City', array (), 'ListsHandlingBundle'))
            ->setCellValue('G1', $translator->trans('Scope', array (), 'ListsHandlingBundle'))
            ->setCellValue('H1', $translator->trans('ServiceOffered', array (), 'ListsHandlingBundle'))
            ->setCellValue('I1', $translator->trans('Chance', array (), 'ListsHandlingBundle'))
            ->setCellValue('J1', $translator->trans('Status', array (), 'ListsHandlingBundle'));
        $phpExcelObject->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
        /* $linkStyleArray = [
          'font' => [
          'color' => ['rgb' => '0000FF'],
          'underline' => 'single'
          ]
          ]; */

        $linkStyleArray = array (
            'font' => array (
                'color' => array ('rgb' => '0000FF'),
                'underline' => 'single'
            )
        );

        $str = 1;
        $menager = '';
        $columnA = '';
        $strStartMerge = 0;
        foreach ($handlings as $handling) {
            ++$str;
            $col = 0;

            if ($menager != $handling['firstName'] . ' ' . $handling['lastName'] . ' ' . $handling['middleName']) {
                $menager = $columnA = $handling['firstName']
                    . ' ' . $handling['lastName']
                    . ' ' . $handling['middleName'];
                $strStartMerge = $str;
            } else {
                $columnA = '';
            }
            $phpExcelObject->getActiveSheet()->mergeCells('A' . $strStartMerge . ':A' . $str);

            $phpExcelObject->getActiveSheet()
                ->setCellValueByColumnAndRow($col, $str, $columnA)
                ->setCellValueByColumnAndRow(++$col, $str, $handling['handlingId']);
            $phpExcelObject->getActiveSheet()->getCellByColumnAndRow($col, $str)->getHyperlink()
                ->setUrl($this->generateUrl(
                    'lists_' . $this->baseRoutePrefix . '_handling_show',
                    array ('id' => $handling['handlingId']),
                    true
                ));

            $phpExcelObject->getActiveSheet()
                ->setCellValueByColumnAndRow(++$col, $str, $handling['organizationName']);
            if ($handling['organizationId']) {
                $phpExcelObject->getActiveSheet()->getCellByColumnAndRow($col, $str)->getHyperlink()
                    ->setUrl(
                        $this->generateUrl(
                            'lists_' . $this->baseRoutePrefix . '_organization_show',
                            array ('id' => $handling['organizationId']),
                            true
                        )
                    );
            }
            $phpExcelObject
                ->getActiveSheet()
                ->setCellValueByColumnAndRow(
                    ++$col,
                    $str,
                    !$handling['handlingCreatedate'] ? '' : $handling['handlingCreatedate']->format('d.m.y')
                )
                ->setCellValueByColumnAndRow(
                    ++$col,
                    $str,
                    !$handling['handlingLastHandlingDate']
                    ?
                    ''
                    :
                    $handling['handlingLastHandlingDate']->format('d.m.y, H:i')
                )
                ->setCellValueByColumnAndRow(++$col, $str, $handling['cityName'])
                ->setCellValueByColumnAndRow(++$col, $str, $handling['scopeName'])
                ->setCellValueByColumnAndRow(++$col, $str, $handling['handlingServiceOffered'])
                ->setCellValueByColumnAndRow(
                    ++$col,
                    $str,
                    $handling['resultPercentageString']
                    ?
                    $handling['resultPercentageString']
                    :
                    $handling['percentageString']
                )
                ->setCellValueByColumnAndRow(++$col, $str, $handling['statusName']);
        }
        $phpExcelObject->getActiveSheet()->getStyle('B2:C' . $str)->applyFromArray($linkStyleArray);
        $phpExcelObject->getActiveSheet()->getStyle('A2:J' . $str)->getAlignment()->setWrapText(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth(13);
        $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $phpExcelObject->getActiveSheet()->getColumnDimension('E')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('G')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('H')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('I')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('J')->setWidth(12);

        $styleArray = array (
            'borders' => array (
                'outline' => array (
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
                    'color' => array ('argb' => '000000')
                ),
                'inside' => array (
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array ('argb' => '000000')
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
        $phpExcelObject->getActiveSheet()->setTitle('Handling');
        $phpExcelObject->setActiveSheetIndex(0);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=handling.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }
    /**
     * Serialize object to json. temporary solution
     *
     * @param object $object
     * @param string $idMethod
     * @param string $method
     *
     * @return mixed[]
     */
    public function serializeObject ($object, $idMethod = '', $method = '')
    {
        $id = $idMethod ? $object->$idMethod() : $object->getId();
        $string = $method ? $object->$method() : (string) $object;

        return array (
            'id' => $id,
            'value' => $id,
            'name' => $string,
            'text' => $string
        );
    }
    /**
     * Executes list action for dashboard
     *
     * @param integer $id Organization.id
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function forOrganizationAction ($id)
    {
        /** @var \Lists\HandlingBundle\Entity\HandlingRepository $handlingRepository */
        $handlingRepository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling');

        /** @var \Doctrine\ORM\Query $handlings */
        $handlings = $handlingRepository->getForOrganization($id);

        return $this->render('ListsHandlingBundle:Handling:forOrganization.html.twig', array (
                'handlings' => $handlings
        ));
    }
    /**
     * Sets wizard handling information to session
     *
     * @param Handling $handling
     */
    public function setWizardHandling ($handling)
    {
        $session = $this->get('session');

        $session->set($this->wizardHandlingNamespace, $handling);
    }
    /**
     * Get wizard handling information to session
     *
     * @return Handling $handling
     */
    public function getWizardHandling ()
    {
        $session = $this->get('session');

        $handling = $session->get($this->wizardHandlingNamespace);

        return $handling ? $handling : null;
    }
    /**
     * Sets wizard organization information to session
     *
     * @param mixed[] $organization
     */
    public function setWizardOrganization ($organization)
    {
        $session = $this->get('session');

        $session->set($this->wizardOrganizationNamespace, $organization);
    }
    /**
     * get wizard organizationId
     *
     * @return mixed|null
     */
    public function getWizardOrganizationId ()
    {
        $organization = $this->getWizardOrganization();

        return isset($organization['organizationId']) ? $organization['organizationId'] : null;
    }
    /**
     * get wizard organizationName
     *
     * @return mixed|null
     */
    public function getWizardOrganizationName ()
    {
        $organization = $this->getWizardOrganization();

        return isset($organization['organizationName']) ? $organization['organizationName'] : null;
    }
    /**
     * Get wizard organization information to session
     *
     * @return mixed[] $organization
     */
    public function getWizardOrganization ()
    {
        $session = $this->get('session');

        $organization = $session->get($this->wizardOrganizationNamespace);

        return $organization;
    }
     /**
     * Checks if valid organization data in session
     *
     * @return boolean
     */
    public function isValidWizardOrganization ()
    {
        $organization = $this->getWizardOrganization();

        if (!isset($organization['organizationId']) || !isset($organization['organizationName'])) {
            return false;
        }

        if (!$organization['organizationId'] && !$organization['organizationName']) {
            return false;
        }

        return true;
    }
    /**
     * Execute wizard step1 action
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function step1Action (Request $request)
    {
        $isPost = $request->getMethod() == 'POST';

        $userId = $this->getUser()->getId();
        $initSelection = array ();
        $noAccess = false;
        if ($isPost) {
            $organization = array ();

            $organization['organizationId'] = $request->request->get('organizationId');
            $organization['organizationName'] = $request->request->get('organizationName');

            $this->setWizardOrganization($organization);

            /** @var Lookup $lookup */
            $lookup = $this->getDoctrine()->getRepository('ListsLookupBundle:Lookup')
                ->findOneBy(array ('lukey' => 'manager_organization'));

            $managerOrganization = is_numeric($organization['organizationId']) ?
                $this->getDoctrine()
                    ->getRepository('ListsOrganizationBundle:OrganizationUser')
                    ->findOneBy(array (
                        'organizationId' => $organization['organizationId'],
                        'roleId' => $lookup->getId(),
                    )) : null;
            $organizationUser = is_numeric($organization['organizationId']) ?
                $this->getDoctrine()
                    ->getRepository('ListsOrganizationBundle:OrganizationUser')
                    ->findOneBy(array (
                        'organizationId' => $organization['organizationId'],
                        'userId' => $userId,
                    )) : null;
            /** @var Translator $translator */
            $translator = $this->container->get('translator');

            if ($this->isValidWizardOrganization() && ($organizationUser || !$managerOrganization)) {
                if (is_numeric($organization['organizationId'])) {
                    $organizationContacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
                        ->getMyOrganizationsContacts($userId, $organization['organizationId'])->getResult();

                    $departmentContacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
                        ->getMyDepartmentByOrganizationContacts($organization['organizationId']);

                    if (count($organizationContacts) != 0 || count($departmentContacts) != 0) {
                        return $this->redirect($this->generateUrl('lists_handling_create_step3'));
                    }
                }

                return $this->redirect($this->generateUrl('lists_handling_create_step2'));
            } elseif (!$organizationUser) {
                $noAccess = $translator->trans(
                    'You can not create a project for the organization refer to',
                    array (),
                    'ListsHandlingBundle'
                );
                if (method_exists($managerOrganization, 'getUser')) {
                    $user = $managerOrganization->getUser();
                    $noAccess .= ' '
                        . $translator->trans(
                            'менеджеру организации',
                            array (),
                            'ListsHandlingBundle'
                        )
                        . ' ' . $user->getFirstName()
                        . ' ' . $user->getLastName()
                        . ' ' . $user->getMiddleName();
                    if (method_exists($user, 'getStaff')) {
                        $noAccess .= ' ' . $user->getStaff()->getMobilephone();
                    }
                    $noAccess .= ' ' . $user->getEmail();
                } else {
                    $noAccess .= ' ' . $translator->trans(
                        'администратору отдела продаж',
                        array (),
                        'ListsHandlingBundle'
                    );
                }
            }
        }

        if ($this->isValidWizardOrganization()) {
            $initSelection = array (
                'id' => $this->getWizardOrganizationId(),
                'text' => $this->getWizardOrganizationName()
            );
        }

        return $this->render('ListsHandlingBundle:Handling:step1.html.twig', array (
                'initSelection' => $initSelection,
                'noAccess' => $noAccess
        ));
    }
    /**
     * Execute wizard step2 action
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function step2Action (Request $request)
    {
        if (!$this->isValidWizardOrganization()) {
            return $this->redirect($this->generateUrl('lists_handling_create_step1'));
        }

        $form = $this->createForm('modelContactOrganizationWizardForm');

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->getConnection()->beginTransaction();

            $user = $this->getUser();

            /** @var ModelContact $contact */
            $contact = $form->getData();

            try {
                if ($this->isNewWizardOrganization()) {
                    /** @var Lookup $lookup */
                    $lookup = $this->getDoctrine()
                        ->getRepository('ListsLookupBundle:Lookup')
                        ->findOneBy(array ('lukey' => 'manager_organization'));

                    $organization = new Organization();
                    $organization->setCreator($user);
                    $organization->setCreatedatetime(new \DateTime());
                    $organization->setName($this->getWizardOrganizationName());
                    $organization->setShortname($this->getWizardOrganizationName());
                    $organization->setAddress($this->getWizardOrganizationName());

                    $em->persist($organization);
                    $em->flush();

                    $em->refresh($organization);

                    $organizationUser = new OrganizationUser();
                    $organizationUser->setRole($lookup);
                    $organizationUser->setOrganization($organization);
                    $organizationUser->setUser($user);
                    $em->persist($organizationUser);
                    $em->flush();

                    $organizationId = $organization->getId();

                    $this->setWizardOrganization(array (
                        'organizationId' => $organization->getId(),
                        'organizationName' => $this->getWizardOrganizationName()
                    ));
                } else {
                    $organizationId = $this->getWizardOrganizationId();

                    $organization = $this->getDoctrine()->getRepository('ListsOrganizationBundle:Organization')
                        ->find($organizationId);
                }

                $contact->setModelId($organizationId);

                $contact->setUser($user);

                $owner = $contact->getOwner();

                if (!$owner) {
                    $contact->setOwner($user);
                }

                $em->persist($contact);
                $em->flush();

                $organizationUsers = $organization->getUsers();

                $userExist = false;
                if (is_array($organizationUsers)) {
                    foreach ($organizationUsers as $organizationUser) {
                        if ($organizationUser->getId() == $user->getId()) {
                            $userExist = true;
                        }
                    }
                }

                if (!$userExist) {
                    $organization->addUser($user);

                    $em->persist($organization);
                    $em->flush();
                }

                $em->getConnection()->commit();
            } catch (\Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                throw $e;
            }

            return $this->redirect($this->generateUrl('lists_handling_create_step3'));
        }

        return $this->render('ListsHandlingBundle:Handling:step2.html.twig', array (
                'form' => $form->createView()
        ));
    }
     /**
     * Execute wizard step3 action
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function step3Action (Request $request)
    {
        $organizationId = $this->getWizardOrganizationId();

        if (!$organizationId) {
            return $this->redirect($this->generateUrl('lists_handling_create_step1'));
        }

        $organization = $this->getDoctrine()->getRepository('ListsOrganizationBundle:Organization')
            ->find($organizationId);

        $user = $this->getUser();

        $form = $this->createForm('handlingSalesWizardForm');

        $form
            ->add('organization', 'text', array (
                'disabled' => true,
                'data' => (string) $organization
            ))
            ->add('user', 'text', array (
                'disabled' => true,
                'data' => (string) $user
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var \Lists\HandlingBundle\Entity\Handling $object */
            $object = $form->getData();

            $object->setUser($user);
            $object->setCreatedatetime(new \DateTime());
            $object->setOrganization($organization);
            $object->addUser($user);

            $this->setWizardHandling($object);

            return $this->redirect($this->generateUrl('lists_handling_create_step4'));
        }

        return $this->render('ListsHandlingBundle:Handling:step3.html.twig', array (
                'form' => $form->createView()
        ));
    }
    /**
     * Execute wizard step4 action
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function step4Action (Request $request)
    {
        $handling = $this->getWizardHandling();

        if (!$handling) {
            return $this->redirect($this->generateUrl('lists_handling_create_step3'));
        }

        $user = $this->getUser();

        $userIds = array ($user->getId());

        $form = $this->createForm('handlingMessageWizardForm');

        $formData = $request->request->get($form->getName());

        $organizationId = $this->getWizardOrganizationId();

        $organization = $this->getDoctrine()->getRepository('ListsOrganizationBundle:Organization')
            ->find($organizationId);

        $form
            ->add('contact', 'entity', array (
                'class' => 'ListsContactBundle:ModelContact',
                'empty_value' => '',
                'required' => true,
                'query_builder' => function (ModelContactRepository $repository) use ($organizationId, $userIds) {
                    return $repository->createQueryBuilder('mc')
                        ->leftJoin('mc.owner', 'owner')
                        ->where('mc.modelName = :modelName')
                        ->andWhere('mc.modelId = :modelId')
                        ->andWhere('owner.id in (:ownerIds)')
                        ->setParameter(':modelName', ModelContactRepository::MODEL_ORGANIZATION)
                        ->setParameter(':modelId', $organizationId)
                        ->setParameter(':ownerIds', $userIds);
                }
        ));
        $form
            ->add('contactnext', 'entity', array (
                'class' => 'ListsContactBundle:ModelContact',
                'empty_value' => '',
                'required' => true,
                'mapped' => false,
                'query_builder' => function (ModelContactRepository $repository) use ($organizationId, $userIds) {
                    return $repository->createQueryBuilder('mc')
                        ->leftJoin('mc.owner', 'owner')
                        ->where('mc.modelName = :modelName')
                        ->andWhere('mc.modelId = :modelId')
                        ->andWhere('owner.id in (:ownerIds)')
                        ->setParameter(':modelName', ModelContactRepository::MODEL_ORGANIZATION)
                        ->setParameter(':modelId', $organizationId)
                        ->setParameter(':ownerIds', $userIds);
                }
        ));

        $form
            ->add('status', 'entity', array (
                'class' => 'ListsHandlingBundle:HandlingStatus',
                'empty_value' => '',
                'mapped' => false,
                'query_builder' => function (\Lists\HandlingBundle\Entity\HandlingStatusRepository $repository) {
                    return $repository->createQueryBuilder('s')
                        ->orderBy('s.sortorder', 'ASC');
                }
        ));

        // Bind form
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->getConnection()->beginTransaction();

            try {
                $newHandling = new Handling();

                //$newHandling->setStatusId($formData['status']);
                $newHandling->setOrganization($organization);

                $resultId = $handling->getResult() ? $handling->getResult()->getId() : null;
                $statusId = $handling->getStatus() ? $handling->getStatus()->getId() : null;
                $typeId = $handling->getType() ? $handling->getType()->getId() : null;

                if ($resultId) {
                    $result = $this->getDoctrine()->getManager()
                        ->getRepository('ListsHandlingBundle:HandlingResult')
                        ->find($resultId);

                    $newHandling->setResult($result);
                }

                if ($statusId) {
                    $status = $this->getDoctrine()->getManager()
                        ->getRepository('ListsHandlingBundle:HandlingStatus')
                        ->find($statusId);

                    $newHandling->setStatus($status);
                }

                if ($typeId) {
                    $type = $this->getDoctrine()->getManager()
                        ->getRepository('ListsHandlingBundle:HandlingType')
                        ->find($typeId);

                    $newHandling->setType($type);
                }

                $newHandling->setUser($user);
                $newHandling->addUser($user);
                $newHandling->setCreatedatetime(new \DateTime());
                $newHandling->setCreatedate($handling->getCreatedate());

                $newHandling->setBudget($handling->getBudget());
                $newHandling->setBudgetClient($handling->getBudgetClient());
                $newHandling->setChance($handling->getChance());
                $newHandling->setDescription($handling->getDescription());

                if ($handling->getHandlingServices()) {
                    foreach ($handling->getHandlingServices() as $service) {
                        $newService = $this->getDoctrine()->getRepository('ListsHandlingBundle:HandlingService')
                            ->find($service->getId());

                        $newHandling->addHandlingService($newService);
                    }
                }
                $lookup = $this->getDoctrine()
                    ->getRepository('ListsLookupBundle:lookup')
                    ->findOneBy(array ('lukey' => 'manager_project'));
                $manager = new HandlingUser();
                $manager->setUser($user);
                $manager->setLookup($lookup);
                $manager->setPart(100);
                $manager->setHandling($newHandling);

                $em->persist($newHandling);
                $em->persist($manager);


                $em->flush();

                $data = $form->getData();

                $data->setCreatedatetime(new \DateTime());
                $data->setUser($user);
                $data->setHandling($newHandling);

                $file = $form['file']->getData();

                if ($file) {
                    $data->upload();
                }

                $em->persist($data);

                // Insert future
                $type = $this->getDoctrine()
                    ->getRepository('ListsHandlingBundle:HandlingMessageType')
                    ->find($formData['nexttype']);

                $nextDatetime = new \DateTime($formData['nextcreatedate']);
                $contactNext = $formData['contactnext'];
                $descriptionNext = $formData['descriptionnext'];
                $statusId = $formData['status'];

                $handlingMessage = new HandlingMessage();
                $handlingMessage->setCreatedate($nextDatetime);
                $handlingMessage->setCreatedatetime(new \DateTime());
                $handlingMessage->setUser($user);
                $handlingMessage->setHandling($newHandling);
                $handlingMessage->setType($type);
                $handlingMessage->setIsBusinessTrip(isset($formData['next_is_business_trip']) ? true : false);
                $handlingMessage->setAdditionalType(HandlingMessage::ADDITIONAL_TYPE_FUTURE_MESSAGE);

                $handlingMessage->setDescription($descriptionNext);

                if ((int) $contactNext) {
                    $contact = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
                        ->find((int) $contactNext);

                    if ($contact) {
                        $handlingMessage->setContact($contact);
                    }
                }

                $em->persist($handlingMessage);
                // $em->flush();

                $handling->setLastHandlingDate($data->getCreatedate());
                $handling->setNextHandlingDate($nextDatetime);

                $newHandling->setStatusId($statusId);

                $em->persist($newHandling);

                $em->flush();

                $em->getConnection()->commit();

                $this->setWizardHandling(null);
                $this->setWizardOrganization(array ());

                return $this->redirect($this->generateUrl('lists_handling_show', array (
                            'id' => $newHandling->getId(),
                            'type' => 'my'
                )));
            } catch (\Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                throw $e;
            }
        }

        return $this->render('ListsHandlingBundle:Handling:step4.html.twig', array (
                'form' => $form->createView()
        ));
    }
}
