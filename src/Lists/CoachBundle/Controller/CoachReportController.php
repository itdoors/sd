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
    protected $filterNamespace = 'coachReportFilterForm';
    protected $service = 'lists_coach.coach.service';
    protected $paginator = 'knp_paginator';

    /**
     * Execute showtabs action
     *
     * @return string
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $id = $user->getId();

        $session = $this->get('session');
        $session->set('userid', $id);

        $service = $this->container->get($this->service);
        $namespace = $this->filterNamespace . $id;

        $tab = $this->getTab($namespace);
        if (!$tab) {
            $tab = 'reports';
            $this->setTab($namespace, $tab);
        }
        $options['coachAdmin'] = $user->hasRole('ROLE_COACHADMIN');

        return $this->render('ListsCoachBundle:Report:index.html.twig', array(
                        'tabs' => $service->getTabs($options),
                        'tab' => $tab,
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
        $user = $this->getUser();
        $session = $this->get('session');
        $userId = $session->get('userid', false);
        $namespace = $this->filterNamespace . $userId;
        $service = $this->container->get($this->service);

        $options['coachAdmin'] = $user->hasRole('ROLE_COACHADMIN');
        $tabs = $service->getTabs($options);
        $tab = $this->getTab($namespace);
        $tabData = $tabs[$tab];

        return $this->render('ListsCoachBundle:Report:showTab' . $tab . '.html.twig', array(
                        'tabData' => $tabData,
                        'namespase' => $namespace,
        ));
    }

    /**
     * Execute list action
     *
     * @return string
     */
    public function listAction()
    {
        $namespase = $this->filterNamespace;
        $em = $this->getDoctrine()->getManager();

        $reports = $em->getRepository('ListsCoachBundle:CoachReport')->getAll();
        $entities = $reports['entity'];
        $count = $reports['count'];

        $page = $this->getPaginator($namespase);
        if (!$page) {
            $page = 1;
        }

        $paginator = $this->container->get($this->paginator);
        $entities->setHint($this->paginator . '.count', $count);
        $pagination = $paginator->paginate($entities, $page, 10);

        return $this->render('ListsCoachBundle:Report:list.html.twig', array(
                'namespase' => $namespase,
                'items' => $pagination
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
        $em = $this->getDoctrine()->getManager();
        $report = $em->getRepository('ListsCoachBundle:CoachReport')->find($id);

        return $this->render('ListsCoachBundle:Report:show.html.twig', array(
                'report' => $report
        ));
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
        $formData = $request->request->get($form->getName());
        $form->handleRequest($request);

        if ($form->isValid() && isset($formData['action']['department'])
                             && isset($formData['action']['individuals'])) {
            try {
                $user = $this->getUser();
                $coachReport = $form->getData();
                $action = $coachReport->getAction();
                $indIds = explode(',', $formData['action']['individuals']);

                $depRepository = $em->getRepository('ListsDepartmentBundle:Departments');
                $indRepository = $em->getRepository('ListsIndividualBundle:Individual');

                $coachReport->setAuthor($user);
                $action->setExecutor($user);
                $action->setDepartment($depRepository->find($formData['action']['department']));
                $action->setIndividuals(
                    new \Doctrine\Common\Collections\ArrayCollection(
                        $indRepository->findBy(array('id' => $indIds))
                    )
                );

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

        $form = $this->createForm('coachReportEditForm', $report);
        $request = $this->getRequest();
        $form->handleRequest($request);
        $formData = $request->request->get($form->getName());

        if ($form->isValid() && isset($formData['action']['individuals'])) {
            try {
                $coachReport = $form->getData();
                $action = $coachReport->getAction();
                $indIds = explode(',', $formData['action']['individuals']);

                $depRepository = $em->getRepository('ListsDepartmentBundle:Departments');
                $indRepository = $em->getRepository('ListsIndividualBundle:Individual');

                $action->setIndividuals(
                    new \Doctrine\Common\Collections\ArrayCollection(
                        $indRepository->findBy(array('id' => $indIds))
                    )
                );

                $em->persist($coachReport);
                $em->flush();
            } catch (\Exception $e) {
                $em->close();
                throw $e;
            }

            return $this->redirect($this->generateUrl('lists_coach_index'));
        }

        return $this->render('ListsCoachBundle:Report:edit.html.twig', array(
                        'report' => $report,
                        'form' => $form->createView(),
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
