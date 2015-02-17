<?php

namespace Lists\ProjectBundle\Controller;

use Lists\ProjectBundle\Controller\ProjectBaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Lists\ProjectBundle\Services\ProjectService;
use Lists\ProjectBundle\Entity\ManagerProjectType;
use Lists\ProjectBundle\Entity\ProjectСommercialTender;

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
                $object = new ProjectСommercialTender();
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
                $email->send(
                    null,
                    'empty-template',
                    array (
                        'users' => array($managers[0]->getUser()->getEmail()),
                        'variables' => array (
                            '${subject}$' => $subject,
                            '${text}$' => $textForSend
                        )
                    )
                );
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

        $repository = $this->getDoctrine()
            ->getRepository('ListsProjectBundle:Project');

        /** @var \Doctrine\ORM\Query $query */
        $query = $repository->getListProjectForTender($user);

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
}
