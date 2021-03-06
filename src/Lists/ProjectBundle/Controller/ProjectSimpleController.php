<?php

namespace Lists\ProjectBundle\Controller;

use Lists\ProjectBundle\Controller\ProjectBaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Lists\ProjectBundle\Services\ProjectService;
use Lists\ProjectBundle\Entity\ManagerProjectType;
use PHPExcel_Style_Border;
use PHPExcel_Style_Alignment;
use Lists\ProjectBundle\Entity\ProjectCommercialTender;

/**
 * Class ProjectSimpleController
 */
class ProjectSimpleController extends ProjectBaseController
{
    protected $filterNamespace = 'project_simple';
    protected $createForm = 'projectSimpleForm';
    protected $nameEntity = 'ProjectSimple';

    /**
     * Executes create action
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function createAction (Request $request)
    {
        if (!$this->nameEntity) {
            $this->createNotFoundException();
        }
        /** @var User $user */
        $user = $this->getUser();
        /** @var ProjectService $service */
        $service = $this->get('lists_project.service');
        $access = $service->checkAccess($user);

        $method = 'canCreate'.$this->nameEntity;
        if (!$access->$method()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm($this->createForm);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $status = $em->getRepository('ListsProjectBundle:Status')->findOneBy(array(
                'alias' => 'study'
            ));
            
            $type = $form->get('type')->getData();
            if ($type == 'simple') {
                $type = 'simple';
                $object = $form->getData();
            } elseif ($type == 'commercial_tender') {
                $services = $form->get('services')->getData();
                $object = new ProjectCommercialTender();
                $object->setOrganization($form->get('organization')->getData());
                $object->setDescription($form->get('description')->getData());
                $object->setCreateDate($form->get('createDate')->getData());
                $object->setSummaWithVAT($form->get('summaWithVAT')->getData());
                $object->setPf($form->get('pf')->getData());
                foreach ($services as $service) {
                    $object->addService($service);
                }
            } elseif ($type == 'electronic_trading') {
                $services = $form->get('services')->getData();
                $object = new \Lists\ProjectBundle\Entity\ProjectElectronicTrading();
                $object->setOrganization($form->get('organization')->getData());
                $object->setDescription($form->get('description')->getData());
                $object->setCreateDate($form->get('createDate')->getData());
                $object->setSummaWithVAT($form->get('summaWithVAT')->getData());
                $object->setPf($form->get('pf')->getData());
                foreach ($services as $service) {
                    $object->addService($service);
                }
            }
            $fileTypes = $em->getRepository('ListsProjectBundle:ProjectFileType')
                ->findBy(array ('group' => 'simple'));
            foreach ($fileTypes as $typeFile) {
                $file = new \Lists\ProjectBundle\Entity\FileProject();
                $file->setProject($object);
                $file->setType($typeFile);
                $em->persist($file);
            }
           
            $object->setStatus($status);
            $isManager = $object->getOrganization()->isManager($user);
            if ($isManager) {
                $object->setStatusAccess(true);
            } else {
                $em->persist($object);
                $em->flush();

                $managers = $object->getOrganization()->getOrganizationUsers();
                $email = $this->container->get('it_doors_email.service');
                $translator = $this->container->get('translator');
                $subject = $translator->trans('Add project in organization', array (), 'ListsProjectBundle')
                    .': '. $object->getOrganization();
                $url = $this->generateUrl('lists_project_'.$object->getDiscr().'_show', array('id' => $object->getId()), true);
                $urlText = '<a href="'.$url.'">'.$url.'</a>';
                $textForSend = $translator->trans('TEXT_FOR_SENT ADD_PROJECT_IN_ORGANIZATION', array (), 'ListsProjectBundle');
                $textForSend = str_replace('${manager}$', $user, $textForSend);
                $textForSend = str_replace('${organization}$', $object->getOrganization(), $textForSend);
                $textForSend = str_replace('${url}$', $urlText, $textForSend);
                foreach ($managers as $manager) {
                    $email->send(
                        null,
                        'empty-template',
                        array (
                            'users' => array($manager->getUser()->getEmail()),
                            'variables' => array (
                                '${subject}$' => $subject,
                                '${text}$' => $textForSend
                            )
                        )
                    );
                }
                $news = new \Lists\ArticleBundle\Entity\Article();
                $news->setUser($user);
                $news->setTitle($subject);
                $news->setTextShort($textForSend);
                $news->setText($textForSend);
                $news->setType('notification');
                $news->setDatePublick(new \DateTime());
                $news->setDateCreate(new \DateTime());
                $em->persist($news);
                $object->setNotification($news);
                foreach ($managers as $manager) {
                    $newsFosUser = new \Lists\ArticleBundle\Entity\NewsFosUser();
                    $newsFosUser->setNews($news);
                    $newsFosUser->setUser($manager->getUser());
                    $newsFosUser->setManual(false);
                    $em->persist($newsFosUser);
                }
                $cron = $this->container->get('it_doors_cron.service');
                $cron->addSendEmails();
            }

            $object->setUserCreated($user);

            $em->persist($object);

            $managerProject = new ManagerProjectType();
            $managerProject->setPart(100);
            $managerProject->setUser($user);
            $managerProject->setProject($object);
            $em->persist($managerProject);

            $em->flush();

            return $this->redirect($this->generateUrl('lists_project_'.$type.'_show', array (
                'id' => $object->getId()
            )));
        }

        return $this->render('ListsProjectBundle:'.$this->nameEntity.':create.html.twig', array (
                'form' => $form->createView()
        ));
    }
    /**
     * indexAction
     *
     * @return Response
     */
    public function indexAction ()
    {
        $filterNamespace = $this->filterNamespace.'_'.strtolower($this->nameEntity);
        if (!$this->nameEntity) {
            $this->createNotFoundException();
        }
        /** @var User $user */
        $user = $this->getUser();
        /** @var ProjectService $service */
        $service = $this->get('lists_project.service');
        $access = $service->checkAccess($user);
        $method = 'canSee'.$this->nameEntity;
        if (!$access->$method()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('ListsProjectBundle:'.$this->nameEntity.':index.html.twig', array (
                'access' => $access,
                'filterNameSpace' => $filterNamespace
        ));
    }
    /**
     * gosListAction
     *
     * @return Response
     */
    public function listAction ()
    {
        if (!$this->nameEntity) {
            $this->createNotFoundException();
        }
        $filterNamespace = $this->filterNamespace.'_'.strtolower($this->nameEntity);
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();
        /** @var ProjectService $service */
        $service = $this->get('lists_project.service');
        $access = $service->checkAccess($user);

        $method = 'canSee'.$this->nameEntity;
        if (!$access->$method()) {
            throw $this->createAccessDeniedException();
        }
        $methodAll = 'canSeeAll'.$this->nameEntity;
        if ($access->$methodAll()) {
            $user = null;
        }
        $baseFilter = $this->container->get('it_doors_ajax.base_filter_service');
        $filters = $baseFilter->getFilters($filterNamespace);

        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $baseFilter->setFilters($filterNamespace, $filters);
        }

        $page = $baseFilter->getPaginator($filterNamespace);
        if (!$page) {
            $page = 1;
        }
        $repository = $this->getDoctrine()
            ->getRepository('ListsProjectBundle:Project');

        /** @var \Doctrine\ORM\Query $query */
        $query = $repository->getListProjectForTender($user, $filters);

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $page,
            25
        );

        return $this->render('ListsProjectBundle:'.$this->nameEntity.':list.html.twig', array(
                'filterNamespace' => $filterNamespace,
                'pagination' => $pagination,
                'access' => $access
            ));
    }
    /**
     * exportExcelAction
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exportExcelAction()
    {
        $filterNamespace = $this->filterNamespace.'_'.strtolower($this->nameEntity);
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $service = $this->get('lists_project.service');
        $access = $service->checkAccess($user);

        $methodAll = 'canSeeAll'.$this->nameEntity;
        if ($access->$methodAll()) {
            $user = null;
        }
        $baseFilter = $this->container->get('it_doors_ajax.base_filter_service');
        $filters = $baseFilter->getFilters($filterNamespace);

        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $baseFilter->setFilters($filterNamespace, $filters);
        }

        $repository = $this->getDoctrine()
            ->getRepository('ListsProjectBundle:Project');

        /** @var \Doctrine\ORM\Query $projects */
        $projects = $repository->getListProjectForExport($user, $filters);
        
        $response = $this->exportToExcel($projects->getResult());

        return $response;
    }
    /**
     * exportToExcel
     *
     * @param array $projects
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function exportToExcel ($projects)
    {
        /** @var Translator $translator */
        $translator = $this->container->get('translator');

        // ask the service for a Excel5
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("Projects")
            ->setLastModifiedBy("Giulio De Donato")
            ->setTitle("Projects")
            ->setSubject("Projects")
            ->setDescription("List of project")
            ->setKeywords("Projects")
            ->setCategory("Projects");
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', $translator->trans('Managers', array (), 'ListsProjectBundle'))
            ->setCellValue('B1', $translator->trans('ID', array (), 'ListsProjectBundle'))
            ->setCellValue('C1', $translator->trans('Name', array (), 'ListsProjectBundle'))
            ->setCellValue('D1', $translator->trans('Date created', array (), 'ListsProjectBundle'))
            ->setCellValue('E1', $translator->trans('Last message date', array (), 'ListsProjectBundle'))
            ->setCellValue('F1', $translator->trans('City', array (), 'ListsProjectBundle'));
        $phpExcelObject->getActiveSheet()->getRowDimension('1')->setRowHeight(40);

        $linkStyleArray = array (
            'font' => array (
                'color' => array ('rgb' => '0000FF'),
                'underline' => 'single'
            )
        );

        $str = 1;
        $manager = '';
        $columnA = '';
        $strStartMerge = 0;
        foreach ($projects as $project) {
            ++$str;
            $col = 0;

            if ($manager != $project->getManagerProject()->getUser()->getLastName().' '.$project->getManagerProject()->getUser()->getFirstName()) {
                $manager = $columnA = $project->getManagerProject()->getUser()->getLastName().' '.$project->getManagerProject()->getUser()->getFirstName();
                $strStartMerge = $str;
            } else {
                $columnA = '';
            }
            $phpExcelObject->getActiveSheet()->mergeCells('A' . $strStartMerge . ':A' . $str);

            $phpExcelObject->getActiveSheet()
                ->setCellValueByColumnAndRow($col, $str, $columnA)
                ->setCellValueByColumnAndRow(++$col, $str, $project->getId());
            $phpExcelObject->getActiveSheet()->getCellByColumnAndRow($col, $str)->getHyperlink()
                ->setUrl($this->generateUrl(
                    'lists_project_' . $project->getDiscr() . '_show',
                    array ('id' => $project->getId()),
                    true
                ));

            $phpExcelObject->getActiveSheet()
                ->setCellValueByColumnAndRow(++$col, $str, $project->getOrganization() == null ? '' : $project->getOrganization()->getName());
            if ($project->getOrganization()) {
                $phpExcelObject->getActiveSheet()->getCellByColumnAndRow($col, $str)->getHyperlink()
                    ->setUrl(
                        $this->generateUrl(
                            'lists_organization_show',
                            array ('id' => $project->getOrganization()->getId()),
                            true
                        )
                    );
            }
            $phpExcelObject
                ->getActiveSheet()
                ->setCellValueByColumnAndRow(
                    ++$col,
                    $str,
                    !$project->getCreateDate() ? '' : $project->getCreateDate()->format('d.m.y')
                )
                ->setCellValueByColumnAndRow(
                    ++$col,
                    $str,
                    !$project->getLastMessageCurrent() || $project->getLastMessageCurrent() && $project->getLastMessageCurrent()->getEventDatetime()
                    ?
                    ''
                    :
                    $project->getLastMessageCurrent()->getEventDatetime()->format('d.m.y, H:i')
                )
                ->setCellValueByColumnAndRow(++$col, $str, $project->getOrganization() == null || $project->getOrganization()->getCity() == null ? '' :$project->getOrganization()->getCity()->getName());
        }
        $phpExcelObject->getActiveSheet()->getStyle('B2:C' . $str)->applyFromArray($linkStyleArray);
        $phpExcelObject->getActiveSheet()->getStyle('A2:F' . $str)->getAlignment()->setWrapText(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth(13);
        $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $phpExcelObject->getActiveSheet()->getColumnDimension('E')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('F')->setWidth(12);

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

        $phpExcelObject->getActiveSheet()->getStyle('A1:F' . $str)->applyFromArray($styleArray);

        $phpExcelObject->getActiveSheet()
            ->getStyle('A2:A' . $str)
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcelObject->getActiveSheet()
            ->getStyle('A1:F1')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcelObject->getActiveSheet()
            ->getStyle('A1:F1')
            ->getAlignment()
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $phpExcelObject->getActiveSheet()
            ->getStyle('B2:F' . $str)
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcelObject->getActiveSheet()->freezePane('AB2');

        $phpExcelObject->getActiveSheet()->getStyle('A1:F' . $str)->getAlignment()->setWrapText(true);
        $phpExcelObject->getActiveSheet()->setShowGridLines(false); //off line
        $phpExcelObject->getActiveSheet()->setTitle('Handling');
        $phpExcelObject->setActiveSheetIndex(0);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=project.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }
    /**
     * reportMessageAction
     * 
     * @param Request $request
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reportMessageAction(Request $request)
    {
        $nameSpaceReport = $this->filterNamespace.'_report_message';
        /** @var \Lists\HandlingBundle\Services\HandlingService $service */
        $service = $this->get('lists_project.service');
        $access = $service->checkAccess($this->getUser());

        if (!$access->canSeeReport()) {
            throw $this->createAccessDeniedException('No access');
        }

        $form = $this->createForm('reportMessageFilter');

        $data = $request->request->get($form->getName());

        $form->handleRequest($request);
        
        $result = array();
        if (sizeof($data)) {
            $session = $this->get('session');
            $session->set($nameSpaceReport, json_encode($data));

            $result = $this->getResultForReport($data);
        }

        return $this->render('ListsProjectBundle:Project:reportMessage.html.twig', array(
                'form' => $form->createView(),
                'results' => $result,
            ));
    }
    /**
     * getResultForReport
     * 
     * @param mixed $data
     */
    private function getResultForReport($data)
    {
        $result = array();
        $em = $this->getDoctrine()->getManager();
        $filters = array();
        $filters['fromDate'] = new \DateTime($data['fromDate']);
        $filters['toDate'] = new \DateTime('23:59:59 '.$data['toDate']);
        if (isset($data['managers'])) {
            $filters['managers'] = $data['managers'];
        }

        $messages = $em->getRepository('ListsProjectBundle:Message')->getList($filters);

        foreach ($messages as $message) {
            $userId = $message->getUser()->getId();
            if (!isset ( $result[$userId] )) {
                $result[$userId] = array();
                $result[$userId]['user'] = $message->getUser();
                $result[$userId]['message'] = array();
                $result[$userId]['count'] = $em->getRepository('ListsProjectBundle:Message')
                    ->getAdvancedCountResult($filters, $userId);
            }
            $result[$userId]['message'][] = $message;
        }

        return $result;
    }
    /**
     * reportExportAction
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reportExportAction()
    {
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $service = $this->get('lists_project.service');
        $access = $service->checkAccess($user);

        if (!$access->canSeeReport()) {
            throw $this->createAccessDeniedException('No access');
        }
        $nameSpaceReport = $this->filterNamespace.'_report_message';
        $session = $this->get('session');
        $data = json_decode($session->get($nameSpaceReport), true);

        $result = $this->getResultForReport($data);

        return $this->exportReportToExcel($result);

    }
    /**
     * Renders Excel
     *
     * @param array $actions
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function exportReportToExcel ($actions)
    {
        /** @var Translator $translator */
        $translator = $this->container->get('translator');

        // ask the service for a Excel5
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("Actions")
            ->setLastModifiedBy("SD")
            ->setTitle("Actions")
            ->setSubject("Actions")
            ->setDescription("Actions message list")
            ->setKeywords("Actions")
            ->setCategory("Actions");
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', $translator->trans('ID', array (), 'ListsProjectBundle'))
            ->setCellValue('B1', $translator->trans('Organization', array (), 'ListsProjectBundle'))
            ->setCellValue('C1', $translator->trans('Date', array (), 'ListsProjectBundle'))
            ->setCellValue('D1', $translator->trans('Type', array (), 'ListsProjectBundle'));
        $phpExcelObject->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
        $linkStyleArray = array (
            'font' => array (
                'color' => array ('rgb' => '0000FF'),
                'underline' => 'single'
            )
        );
        $str = 1;
        foreach ($actions as $result) {
            ++$str;
            $col = 0;

            $phpExcelObject->getActiveSheet()->mergeCells('A' . $str . ':D' . $str);
            $phpExcelObject->getActiveSheet()->setCellValue('A'.$str, $result['user']->getFirstName() . ' '.$result['user']->getLastName());
            $actionId = null;
            foreach ($result['message'] as $message) {
                $col = 0;
                if ($actionId != $message->getType()->getId()) {
                    $actionId = $message->getType()->getId();
                    foreach ($result['count'] as $actionCount) {
                        if ($message->getType()->getName() == $actionCount['typeAction']) {
                            ++$str;
                            $phpExcelObject->getActiveSheet()->setCellValue('D'.$str, $actionCount['typeAction']. '('.$actionCount['countAction'].')');
                            $phpExcelObject->getActiveSheet()->mergeCells('A' . ($str) . ':C' . $str);
                        }
                    }
                }
                $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col, ++$str, $message->getId());
                $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow(++$col, $str, $message->getProject()->getOrganization()->getName());
                $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow(++$col, $str, $message->getEventDateTime()->format('d.m.Y H:i'));
                $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow(++$col, $str, $message->getType()->getName());
            }
        }
        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth(6);
        $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth(60);
        $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $phpExcelObject->getActiveSheet()->getStyle('B2:C' . $str)->applyFromArray($linkStyleArray);
        $phpExcelObject->getActiveSheet()->getStyle('A2:D' . $str)->getAlignment()->setWrapText(true);
   
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

        $phpExcelObject->getActiveSheet()->getStyle('A1:D' . $str)->applyFromArray($styleArray);

        $phpExcelObject->getActiveSheet()
            ->getStyle('A2:A' . $str)
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcelObject->getActiveSheet()
            ->getStyle('A1:D1')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcelObject->getActiveSheet()
            ->getStyle('A1:D1')
            ->getAlignment()
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $phpExcelObject->getActiveSheet()
            ->getStyle('B2:D' . $str)
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcelObject->getActiveSheet()->freezePane('AB2');

        $phpExcelObject->getActiveSheet()->getStyle('A1:D' . $str)->getAlignment()->setWrapText(true);
        $phpExcelObject->getActiveSheet()->setShowGridLines(false); //off line
        $phpExcelObject->getActiveSheet()->setTitle('report');
        $phpExcelObject->setActiveSheetIndex(0);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=actions.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }
}
