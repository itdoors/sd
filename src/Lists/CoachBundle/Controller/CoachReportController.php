<?php

namespace Lists\CoachBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CoachReportController
 */
class CoachReportController extends BaseController
{
    protected $filterNamespace = 'stuffFilterForm';
    /** @var InvoiceService $service */
    protected $service = 'lists_coach.coach.service';
    /** @var KnpPaginatorBundle $paginator */
    protected $paginator = 'knp_paginator';

    /**
     * Execute showtabs action
     *
     * @return string
     */
    public function indexAction()
    {
        /** @var UserRepository $user */
        $user = $this->get('sd_user.repository');
        $id = $this->getUser()->getId();

        /** @var User $item */
        $item = $user->find($id);
        if (!$item) {
            return $this->redirect($this->generateUrl('sd_user_stuff'));
        }
        /** @var Session $session */
        $session = $this->get('session');
        $session->set('userid', $id);

        $isCoachAdmin = $this->getUser()->hasRole('ROLE_COACHADMIN');

        /** @var UserService $service */
        $service = $this->container->get($this->service);

        $namespace = $this->filterNamespace . $id;

        $tab = $this->getTab($namespace);
        if (!$tab) {
            $tab = 'reports';
            $this->setTab($namespace, $tab);
        }

        $options['coachAdmin'] = true;
        $tabs = $service->getTabs($options);

        return $this->render('ListsCoachBundle:Report:index.html.twig', array(
                        'tabs' => $tabs,
                        'tab' => $tab,
                        'item' => $item,
                        'namespace' => $namespace
        ));
    }

    /**
     * Execute showtabs action
     *
     * @return string
     */
    public function showtabsAction()
    {
        /** @var Session $session */
        $session = $this->get('session');
        $userId = $session->get('userid', false);

        if (!$userId) {
            return $this->redirect($this->generateUrl('lists_coach_report_add'));
        }
        /** @var UserRepository $user */
        $user = $this->get('sd_user.repository');

        /** @var User $item */
        $item = $user->getStuffById($userId);

        $namespace = $this->filterNamespace . $userId;

        $tab = $this->getTab($namespace);

        $service = $this->container->get($this->service);
        $options['coachAdmin'] = true;
        $tabs = $service->getTabs($options);
        $tabData = $tabs[$tab];

        $isAdmin = $this->getUser()->hasRole('ROLE_HRADMIN');

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var Usercontactinfo $usercontactinfo */
        $usercontactinfo = $em->getRepository('SDUserBundle:Usercontactinfo')->findBy(array('user' => $userId));

        return $this->render('ListsCoachBundle:Report:showTab' . $tab . '.html.twig', array(
                        'item' => $item,
                        'isAdmin' => $isAdmin,
                        'tabData' => $tabData,
                        'namespase' => $namespace,
                        'usercontactinfo' => $usercontactinfo,
        ));
    }

    /**
     * Execute list action
     *
     * @return string
     */
    public function listAction()
    {
//         $namespase = $this->filterNamespace;
//         $filters = $this->getFilters($namespase);
//         if (empty($filters)) {
//             /** @var EntityManager $em */
//             $em = $this->getDoctrine()->getManager();
//             $status = $em->getRepository('ListsLookupBundle:Lookup')
//                 ->findOneBy(array('lukey' => 'worked'));
//             $filters['status'] = $status->getId();
//             $this->setFilters($namespase, $filters);
//         }
//         $users = $this->get('sd_user.repository')->getAllForUserQuery($filters);
//         $entities = $users['entity'];
//         $count = $users['count'];

//         $page = $this->getPaginator($namespase);
//         if (!$page) {
//             $page = 1;
//         }

//         $paginator = $this->container->get($this->paginator);
//         $entities->setHint($this->paginator . '.count', $count);
//         $pagination = $paginator->paginate($entities, $page, 10);

        $em = $this->getDoctrine()->getManager();
        $reports = $em->getRepository('ListsCoachBundle:CoachReport')->findAll();

        return $this->render('ListsCoachBundle:Report:list.html.twig', array(
//                 'namespase' => $namespase,
//                 'items' => $pagination,
                'items' => $reports
        ));
    }

    /**
     * Execute show action
     *
     * @param int $id
     *
     * @return string
     */
    public function showAction($id)
    {
        return $this->render('ListsCoachBundle:Report:show.html.twig', array());
    }

    /**
     * Execute add action
     *
     * @param Request $request
     * 
     * @return string
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('coachReportForm');
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $user = $this->getUser();
                $coachReport = $form->getData();
                $action = $coachReport->getAction();
                $formData = $request->request->get($form->getName());var_dump($coachReport);die();

                $depRepository = $em->getRepository('ListsDepartmentBundle:Departments');

                $coachReport->setAuthor($user);
                $action->setExecutor($user);
                $action->setDepartment($depRepository->find($formData['action']['department']));

                $em->persist($coachReport);
                $em->flush();
            } catch (\Exception $e) {
                $em->close();
                throw $e;
            }

            return $this->redirect($this->generateUrl('lists_coach_index'));
        }

        return $this->render('ListsCoachBundle:Report:add.html.twig', array(
                        'form' => $form->createView()
        ));
    }

    /**
     * Execute edit action
     *
     * @param int $id
     *
     * @return string
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $report = $em->getRepository('ListsCoachBundle:CoachReport')->find($id);

        if (!$report) {
            throw $this->createNotFoundException(
                'Unable to find CoachReport entity.'
            );
        }
        if ($report->getAuthor() != $this->getUser()) {
            throw new AccessDeniedException(
                'You have no permission to edit this report!'
            );
        }

        $form = $this->createForm('coachReportForm', $report);
        $request = $this->getRequest();
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $coachReport = $form->getData();
                $formData = $request->request->get($form->getName());

                $depRepository = $em->getRepository('ListsDepartmentBundle:Departments');

                $action->setDepartment($depRepository->find($formData['action']['department']));

                $em->persist($coachReport);
                $em->flush();
            } catch (\Exception $e) {
                $em->close();
                throw $e;
            }

            return $this->redirect($this->generateUrl('lists_coach_index'));
        }

//         $form->get('city')->setData($report->getAction()->getDepartments()->)

        return $this->render('ListsCoachBundle:Report:edit.html.twig', array(
                        'report' => $report,
                        'form' => $form->createView()
        ));
    }

    /**
     * Execute delete action
     *
     * @param int $id
     *
     * @return string
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $report = $em->getRepository('ListsCoachBundle:CoachReport')->find($id);

        $em->remove($report);
        $em->flush();

        return $this->redirect($this->generateUrl('lists_coach_index'));
    }

    /**
     * Saves uploaded image
     *
     * @param Request $request
     *
     * @return string path to image
     */
    public function uploadAction(Request $request)
    {
        $name = $this->randomString();
        $reportsFilePath = $this->container->getParameter('reports.file.path');
        $ext = explode('.', $_FILES['file']['name']);
        $directory = $this->container->getParameter('project.web.dir');
        $directory .= $reportsFilePath;
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        $ext = explode('.', $_FILES['file']['name']);
        $filename = $name . '.' . $ext[1];
        $destination = $directory . $filename;
        $location = $_FILES["file"]["tmp_name"];
        move_uploaded_file($location, $destination);
        $directory = $reportsFilePath;
        $destination = $directory . $filename;

        return new Response($destination);
    }

    /**
     * Random string
     *
     * @return string
     */
    private function randomString()
    {
        return md5(rand(100, 200));
    }
}
