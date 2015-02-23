<?php

namespace Lists\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManager;
use Lists\ProjectBundle\Services\ProjectService;
use SD\UserBundle\Entity\User;
use Lists\ProjectBundle\Entity\ManagerProjectType;
use ITDoors\CommonBundle\Services\BaseService;
use PHPExcel_Style_Border;
use PHPExcel_Style_Alignment;

/**
 * Class ProjectBaseController
 */
class ProjectBaseController extends Controller
{
    protected $filterNamespace = 'project';
    protected $createForm = 'projectForm';
    protected $nameEntity = null;
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
            
            $object = $form->getData();
            $object->setUserCreated($user);
            $em->persist($object);

            $managerProject = new ManagerProjectType();
            $managerProject->setPart(100);
            $managerProject->setUser($user);
            $managerProject->setProject($object);
            $em->persist($managerProject);

            $em->flush();

            return $this->redirect($this->generateUrl('lists_project_'.strtolower($this->nameEntity).'_show', array (
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
                'access' => $access
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
//        $filters = $baseFilter->getFilters($filterNamespace);

//        if (empty($filters)) {
//            $filters['isFired'] = 'No fired';
//            $this->setFilters($filterNamespace, $filters);
//        }

        $page = $baseFilter->getPaginator($filterNamespace);
        if (!$page) {
            $page = 1;
        }

        $methodRepository = 'getList'.$this->nameEntity;
        /** @var \Doctrine\ORM\Query $query */
        $query = $this->getDoctrine()
            ->getRepository('ListsProjectBundle:'.$this->nameEntity)
            ->$methodRepository($user);

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
     * Executes show action
     *
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction ($id)
    {
        if (!$this->nameEntity) {
            $this->createNotFoundException();
        }
        $em = $this->getDoctrine()->getManager();
        /** @var BaseService $baseService */
//        $baseService = $this->get('itdoors_common.base.service');
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $repository = $em->getRepository('ListsProjectBundle:'.$this->nameEntity);
        $methodGet = 'get'.$this->nameEntity;
        $object = $repository->$methodGet($id);

        $service = $this->get('lists_project.service');
        $access = $service->checkAccess($user, $object);

        $methodSee = 'canSee'.$this->nameEntity;
        if (!$access->$methodSee()) {
            throw $this->createAccessDeniedException();
        }
        $organization = $object->getOrganization();
        $serviceOrganization = $this->get('lists_organization.service');
        $accessOrganization = $serviceOrganization->checkAccess($user, $organization);

        $lookups = $em->getRepository('ListsLookupBundle:Lookup')->getGroupOrganizationQuery()->getQuery()->getResult();

        return $this->render('ListsProjectBundle:'.$this->nameEntity.':show.html.twig', array (
                'accessOrganization' => $accessOrganization,
                'organization' => $organization,
                'object' => $object,
                'access' => $access,
                'lookups' => $lookups
        ));
    }
    /**
     * Executes editAction
     *
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction ($id)
    {
        if (!$this->nameEntity) {
            $this->createNotFoundException();
        }
        $em = $this->getDoctrine()->getManager();
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $repository = $em->getRepository('ListsProjectBundle:'.$this->nameEntity);
        $methodGet = 'get'.$this->nameEntity;
        $object = $repository->$methodGet($id);

        $service = $this->get('lists_project.service');
        $access = $service->checkAccess($user, $object);

        $methodSee = 'canSee'.$this->nameEntity;
        if (!$access->$methodSee()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('ListsProjectBundle:'.$this->nameEntity.':Tab/edit.html.twig', array (
                'object' => $object,
                'access' => $access
        ));
    }
    /**
     * Renders managers list
     *
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function managersAction ($id)
    {
        /** @var \Lists\ProjectBundle\Entity\ProjectRepository $project */
        $project = $this->getDoctrine()
            ->getRepository('ListsProjectBundle:Project')->find($id);

        $managers = $project->getManagers();
//        foreach ($managers as $manager) {
//            if ($manager instanceof ManagerProjectType) {
//                echo 'менеджерsdfsf';die;
//            }
//        }

        $service = $this->get('lists_project.service');
        $access = $service->checkAccess($this->getUser(), $project);

        return $this->render('ListsProjectBundle:Project:listManagers.html.twig', array (
                'managerProject' => $managers[0],
                'managers' => $managers,
                'project' => $project,
                'access' => $access
        ));
    }
    /**
     * Executes showDocumentsAction
     *
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showDocumentsAction ($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $repository = $em->getRepository('ListsProjectBundle:'.$this->nameEntity);
        $methodGet = 'get'.$this->nameEntity;
        $object = $repository->$methodGet($id);

        $service = $this->get('lists_project.service');
        $access = $service->checkAccess($user, $object);

       $methodSee = 'canSee'.$this->nameEntity;
        if (!$access->$methodSee()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('ListsProjectBundle:'.$this->nameEntity.':Tab/documents.html.twig', array (
                'object' => $object,
                'access' => $access
        ));
    }
     /**
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function messagesListAction ($id)
    {
        $messages = $this->getDoctrine()
            ->getRepository('ListsProjectBundle:Message')->getMessagesByProjectId($id);

//        $usersFromTheirSide = array ();
//        $usersFromOurSide = array ();
//        $calls = array ();
//        foreach ($messages as $message) {
//            $usersFromTheirSideTemp = $this->getDoctrine()
//                ->getRepository('ListsHandlingBundle:HandlingMessageModelContact')
//                ->findBy(array (
//                'handlingMessage' => $message
//            ));
//
//            $usersFromTheirSide['message' . $message->getId()] = $usersFromTheirSideTemp;
//
//            $usersFromOurSideTemp = $this->getDoctrine()
//                ->getRepository('ListsHandlingBundle:HandlingMessageHandlingUser')
//                ->findBy(array (
//                'handlingMessage' => $message
//            ));
//
//            $usersFromOurSide['message' . $message->getId()] = $usersFromOurSideTemp;
//
//            if ($message->getType() && $message->getType()->getId() === 1) {
//                $call = $this->getDoctrine()
//                    ->getRepository('ITDoorsSipBundle:Call')
//                    ->findOneBy(array('modelName' => 'handling_message' ,'modelId' => $message->getId()));
//                if ($call) {
//                    $calls[$message->getId()] = $call;
//                }
//            }
//        }

        return $this->render('ListsProjectBundle:Project:messagesList.html.twig', array (
                'messages' => $messages,
//                'usersFromTheirSide' => $usersFromTheirSide,
//                'usersFromOurSide' => $usersFromOurSide,
//                'calls' => $calls
        ));
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
        $projects = $this->getDoctrine()
            ->getRepository('ListsProjectBundle:Project')->getForOrganization($id);

        return $this->render('ListsProjectBundle:Project:Tab/forOrganization.html.twig', array (
                'projects' => $projects
        ));
    }
    /**
     * reportAction
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reportAction ()
    {
        $filterNameSpace = $this->filterNamespace .'_report';

        return $this->render('ListsProjectBundle:Project:report.html.twig', array (
                'filterNameSpace' => $filterNameSpace
        ));
    }
    /**
     * reportListAction
     * 
     * @param string $type electronic|commercial|firstMeet
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reportListAction ($type)
    {
        $filterNameSpace = $this->filterNamespace .'_report';
        $em = $this->getDoctrine()->getManager();
        $baseFilter = $this->container->get('it_doors_ajax.base_filter_service');
        $filters = $baseFilter->getFilters($filterNameSpace);

        if (empty($filters) || empty($filters['managers']) && empty($filters['daterange']['text']) ) {
            $results = null;
        } else {
            $typeFile = $em->getRepository('ListsProjectBundle:ProjectFileType')
                ->findOneBy(array('alias' => 'commercial_offer'))
                ->getId();
            $typeMessage = $em->getRepository('ListsProjectBundle:MessageType')
                ->findOneBy(array('slug' => 'first_meet'))
                ->getId();
            /** @var Query $projectQuery */
            $projectQuery = $em->getRepository('ListsProjectBundle:Project')->getListProjectForReport($type, $typeFile, $typeMessage, $filters);
            $results = $projectQuery->getResult();
        }

        return $this->render('ListsProjectBundle:Project:Tab/report'.ucfirst($type).'List.html.twig', array (
                'filterNameSpace' => $filterNameSpace,
                'type' => $type,
                'results' => $results
        ));
    }
    /**
     * reportListExportAction
     * 
     * @param string $type electronic|commercial|firstMeet
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reportListExportAction ($type)
    {
        $filterNameSpace = $this->filterNamespace .'_report';
        $em = $this->getDoctrine()->getManager();
        $baseFilter = $this->container->get('it_doors_ajax.base_filter_service');
        $filters = $baseFilter->getFilters($filterNameSpace);

        if (empty($filters) || empty($filters['managers']) && empty($filters['daterange']['text']) ) {
            $results = null;
        } else {
            $typeFile = $em->getRepository('ListsProjectBundle:ProjectFileType')
                ->findOneBy(array('alias' => 'commercial_offer'))
                ->getId();
            $typeMessage = $em->getRepository('ListsProjectBundle:MessageType')
                ->findOneBy(array('slug' => 'first_meet'))
                ->getId();
            /** @var Query $projectQuery */
            $projectQuery = $em->getRepository('ListsProjectBundle:Project')->getListProjectForReport($type, $typeFile, $typeMessage, $filters);
            $results = $projectQuery->getResult();
        }
        $method = 'exportToExcel'.ucfirst($type);
        $response = $this->$method($results, $type);

        return $response;
    }
    /**
     * exportToExcel
     *
     * @param array $projects
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function exportToExcelFirstMeet ($projects, $type)
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
            ->setCellValue('A1', $translator->trans('ID', array (), 'ListsProjectBundle'))
            ->setCellValue('B1', $translator->trans('Organization', array (), 'ListsProjectBundle'))
            ->setCellValue('C1', $translator->trans('Manager', array (), 'ListsProjectBundle'))
            ->setCellValue('D1', $translator->trans('Summa with VAT', array (), 'ListsProjectBundle'))
            ->setCellValue('E1', $translator->trans('PF1', array (), 'ListsProjectBundle'))
            ->setCellValue('F1', $translator->trans('Description', array (), 'ListsProjectBundle'));
        $phpExcelObject->getActiveSheet()->getRowDimension('1')->setRowHeight(40);

        $linkStyleArray = array (
            'font' => array (
                'color' => array ('rgb' => '0000FF'),
                'underline' => 'single'
            )
        );
        $str = 1;
        foreach ($projects as $valProject) {
            $project = $valProject['project'];
            ++$str;
            $col = 0;

            
            $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col, $str, $project->getId());
            $phpExcelObject->getActiveSheet()->getCellByColumnAndRow($col, $str)->getHyperlink()
                ->setUrl($this->generateUrl(
                    'lists_project_' . $project->getDiscr() . '_show',
                    array ('id' => $project->getId()),
                    true
                ));
            $service = '';
            $services = $project->getServices();
            foreach ($services as $key => $val) {
                if($key > 0) {
                    $service .= ', ';
                }
                $service .= $val->getName();
            }
            $phpExcelObject
                ->getActiveSheet()
                ->setCellValueByColumnAndRow(++$col, $str, $project->getOrganization()->getName())
                ->setCellValueByColumnAndRow(++$col, $str, $project->getManagerProject()->getUser()->getLastName().' '.$project->getManagerProject()->getUser()->getFirstName())
                ->setCellValueByColumnAndRow(++$col, $str, $project->getSummaWithVat())
                ->setCellValueByColumnAndRow(++$col, $str, $project->getPf())
                ->setCellValueByColumnAndRow(++$col, $str, $valProject['descriptionMessage']);
        }
        $phpExcelObject->getActiveSheet()->getStyle('B2:C' . $str)->applyFromArray($linkStyleArray);
        $phpExcelObject->getActiveSheet()->getStyle('A2:J' . $str)->getAlignment()->setWrapText(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth(7);
        $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $phpExcelObject->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $phpExcelObject->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $phpExcelObject->getActiveSheet()->getColumnDimension('G')->setWidth(25);

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

        $phpExcelObject->getActiveSheet()->getStyle('A1:G' . $str)->applyFromArray($styleArray);

        $phpExcelObject->getActiveSheet()
            ->getStyle('A2:A' . $str)
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcelObject->getActiveSheet()
            ->getStyle('A1:G1')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcelObject->getActiveSheet()
            ->getStyle('A1:G1')
            ->getAlignment()
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $phpExcelObject->getActiveSheet()
            ->getStyle('B2:G' . $str)
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcelObject->getActiveSheet()->freezePane('AB2');

        $phpExcelObject->getActiveSheet()->getStyle('A1:G' . $str)->getAlignment()->setWrapText(true);
        $phpExcelObject->getActiveSheet()->setShowGridLines(false); //off line
        $phpExcelObject->getActiveSheet()->setTitle('Project');
        $phpExcelObject->setActiveSheetIndex(0);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename='.$type.'.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }
    /**
     * exportToExcel
     *
     * @param array $projects
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function exportToExcelElectronic ($projects, $type)
    {
        return $this->exportToExcelCommercial($projects, $type);
    }
    /**
     * exportToExcel
     *
     * @param array $projects
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function exportToExcelCommercial ($projects, $type)
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
            ->setCellValue('A1', $translator->trans('ID', array (), 'ListsProjectBundle'))
            ->setCellValue('B1', $translator->trans('Responsible', array (), 'ListsProjectBundle'))
            ->setCellValue('C1', $translator->trans('Organization', array (), 'ListsProjectBundle'))
            ->setCellValue('D1', $translator->trans('Summa with VAT', array (), 'ListsProjectBundle'))
            ->setCellValue('E1', $translator->trans('PF1', array (), 'ListsProjectBundle'))
            ->setCellValue('F1', $translator->trans('Duration of the contract', array (), 'ListsProjectBundle'))
            ->setCellValue('G1', $translator->trans('Cost of purchase', array (), 'ListsProjectBundle'));
        $phpExcelObject->getActiveSheet()->getRowDimension('1')->setRowHeight(40);

        $linkStyleArray = array (
            'font' => array (
                'color' => array ('rgb' => '0000FF'),
                'underline' => 'single'
            )
        );
        $str = 1;
        foreach ($projects as $project) {
            ++$str;
            $col = 0;

            
            $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col, $str, $project->getId());
            $phpExcelObject->getActiveSheet()->getCellByColumnAndRow($col, $str)->getHyperlink()
                ->setUrl($this->generateUrl(
                    'lists_project_' . $project->getDiscr() . '_show',
                    array ('id' => $project->getId()),
                    true
                ));
            $service = '';
            $services = $project->getServices();
            foreach ($services as $key => $val) {
                if($key > 0) {
                    $service .= ', ';
                }
                $service .= $val->getName();
            }
            $phpExcelObject
                ->getActiveSheet()
                ->setCellValueByColumnAndRow(++$col, $str, $project->getManagerProject()->getUser()->getLastName().' '.$project->getManagerProject()->getUser()->getFirstName())
                ->setCellValueByColumnAndRow(++$col, $str, $project->getOrganization()->getName())
                ->setCellValueByColumnAndRow(++$col, $str, $project->getSummaWithVat())
                ->setCellValueByColumnAndRow(++$col, $str, $project->getPf())
                ->setCellValueByColumnAndRow(++$col, $str, '')
                ->setCellValueByColumnAndRow(++$col, $str, $service);
        }
        $phpExcelObject->getActiveSheet()->getStyle('B2:C' . $str)->applyFromArray($linkStyleArray);
        $phpExcelObject->getActiveSheet()->getStyle('A2:J' . $str)->getAlignment()->setWrapText(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth(7);
        $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $phpExcelObject->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $phpExcelObject->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $phpExcelObject->getActiveSheet()->getColumnDimension('G')->setWidth(25);

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

        $phpExcelObject->getActiveSheet()->getStyle('A1:G' . $str)->applyFromArray($styleArray);

        $phpExcelObject->getActiveSheet()
            ->getStyle('A2:A' . $str)
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcelObject->getActiveSheet()
            ->getStyle('A1:G1')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcelObject->getActiveSheet()
            ->getStyle('A1:G1')
            ->getAlignment()
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $phpExcelObject->getActiveSheet()
            ->getStyle('B2:G' . $str)
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcelObject->getActiveSheet()->freezePane('AB2');

        $phpExcelObject->getActiveSheet()->getStyle('A1:G' . $str)->getAlignment()->setWrapText(true);
        $phpExcelObject->getActiveSheet()->setShowGridLines(false); //off line
        $phpExcelObject->getActiveSheet()->setTitle('Project');
        $phpExcelObject->setActiveSheetIndex(0);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename='.$type.'.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }
}
