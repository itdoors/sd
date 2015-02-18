<?php

namespace Lists\ProjectBundle\Controller;

use Lists\ProjectBundle\Controller\ProjectBaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Lists\ProjectBundle\Services\ProjectService;
use Lists\ProjectBundle\Entity\ManagerProjectType;
use Lists\ProjectBundle\Filter\ProjectFilter;
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
                foreach ($services as $service) {
                    $object->addService($service);
                }
            } elseif ($type == 'electronic_trading') {
                $services = $form->get('services')->getData();
                $object = new \Lists\ProjectBundle\Entity\ProjectElectronicTrading();
                $object->setOrganization($form->get('organization')->getData());
                $object->setDescription($form->get('description')->getData());
                $object->setCreateDate($form->get('createDate')->getData());
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
            10
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
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $service = $this->get('lists_project.service');
        $access = $service->checkAccess($user);

        $userId = $user->getId();
        if ($access->canExportToExelAll) {
            $userId = null;
        }
         // Get organization filter
//        $filters = $this->getFilters();

        $repository = $this->getDoctrine()
            ->getRepository('ListsProjectBundle:Project');

        /** @var \Doctrine\ORM\Query $projects */
        $projects = $repository->getListProjectForTender($user);

        $response = $this->exportToExcel($projects);

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
            ->setCellValue('D1', $translator->trans('Createdatetime', array (), 'ListsProjectBundle'))
            ->setCellValue('E1', $translator->trans('LastHandlingDate', array (), 'ListsProjectBundle'))
            ->setCellValue('F1', $translator->trans('City', array (), 'ListsProjectBundle'))
            ->setCellValue('G1', $translator->trans('Scope', array (), 'ListsProjectBundle'))
            ->setCellValue('H1', $translator->trans('ServiceOffered', array (), 'ListsProjectBundle'))
            ->setCellValue('I1', $translator->trans('Chance', array (), 'ListsProjectBundle'))
            ->setCellValue('J1', $translator->trans('Status', array (), 'ListsProjectBundle'));
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

            if ($manager != $project->getUser()) {
                $manager = $columnA = $project->getUser();
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
                    'lists_prject_' . $project->getDiscr() . '_show',
                    array ('id' => $project->getId()),
                    true
                ));

            $phpExcelObject->getActiveSheet()
                ->setCellValueByColumnAndRow(++$col, $str, $project->getOrganization());
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
}
