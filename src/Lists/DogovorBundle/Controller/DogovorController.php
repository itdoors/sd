<?php

namespace Lists\DogovorBundle\Controller;

use Lists\DogovorBundle\Entity\DogovorRepository;
use ITDoors\CommonBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DogovorController
 */
class DogovorController extends BaseController
{
    protected $filterNamespace = 'base.dogovor.filters';
    protected $filterFormName = 'dogovorFilterForm';
    protected $baseRoute = 'lists_dogovor_dogovor_index';
    protected $baseRoutePrefix = 'dogovor';
    protected $baseTemplate = 'Dogovor';
     /** @var KnpPaginatorBundle $paginator */
    protected $paginator = 'knp_paginator';

    /**
     * indexAction
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $service = $this->get('lists_dogovor.service');
        $access = $service->checkAccess($this->getUser());

        if (!$access->canSeeList()) {
            throw new \Exception('No access', 403);
        }

        $page = $this->get('request')->query->get('page', 1);

        $filterForm = $this->processFilters();

        /** @var \Lists\DogovorBundle\Entity\DogovorRepository $repository */
        $repository = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor');

        /** @var \Doctrine\ORM\Query */
        $query = $repository->getAllForDogovorQuery($this->getFilters());

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $page,
            20
        );

        return $this->render('ListsDogovorBundle:' . $this->baseTemplate. ':index.html.twig', array(
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView(),
            'filterFormName' => $this->filterFormName,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }
    /**
     * listDangerAction
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listDangerAction()
    {
        $service = $this->get('lists_dogovor.service');
        $access = $service->checkAccess($this->getUser());

        if (!$access->canSeeDanger()) {
            throw new \Exception('No access', 403);
        }

        $baseFilter = $this->container->get('it_doors_ajax.base_filter_service');

        $namespace = 'dogovorDanger';

        /** @var \Lists\DogovorBundle\Entity\DogovorRepository $repository */
        $repository = $this->getDoctrine()->getRepository('ListsDogovorBundle:Dogovor');
        $idManager = null;
        if ($this->getUser()->hasRole('ROLE_SALES')) {
            $idManager = $this->getUser()->getId();
        }

        $items = $repository->getAllDanger($idManager);
        $entities = $items['entities'];
        $count = $items['count'];

        $page = $baseFilter->getPaginator($namespace);
        if (!$page) {
            $page = 1;
        }
        $paginator = $this->container->get($this->paginator);
        $entities->setHint($this->paginator . '.count', $count);
        $pagination = $paginator->paginate($entities, $page, 20);

        return $this->render('ListsDogovorBundle:Dogovor:listDanger.html.twig', array(
            'pagination' => $pagination,
            'namespace' => $namespace,
            'access' => $access
        ));
    }
    /**
     * Renders single element of dogovor list
     *
     * @param int $id
     *
     * @return string
     */
    public function elementDangerAction($id)
    {
        $service = $this->get('lists_dogovor.service');
        $access = $service->checkAccess($this->getUser());

        if (!$access->canSeeDanger()) {
            throw new \Exception('No access', 403);
        }
        /** @var DogovorRepository $dr */
        $dr = $this->get('lists_dogovor.repository');

        $itemQuery = $dr->getAllForDogovorQuery(array(), $id);

        $item = $itemQuery->getSingleResult();

        return $this->render('ListsDogovorBundle:Dogovor:elementDanger.html.twig', array(
            'item' => $item,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix
        ));
    }

    /**
     * Executes new action
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $service = $this->get('lists_dogovor.service');
        $access = $service->checkAccess($this->getUser());

        if (!$access->canAddDogovor()) {
            return $this->render('ListsDogovorBundle:Dogovor:noAccess.html.twig');
        }
        $form = $this->createForm('dogovorForm');

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->getUser();

            /** @var \Lists\DogovorBundle\Entity\Dogovor $object */
            $object = $form->getData();

            $file = $form['file']->getData();

            if ($file) {
                $object->upload();
            }

            $object->setUser($user);
            $object->setCreateDateTime(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirect($this->generateUrl('lists_dogovor_dogovor_show', array(
                'id' => $object->getId()
            )));
        }

        return $this->render('ListsDogovorBundle:Dogovor:new.html.twig', array(
            'form' => $form->createView(),
            'filterFormName' => $this->filterFormName,
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'baseTemplate' => $this->baseTemplate
        ));
    }

    /**
     * Executes show action
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        /** @var DogovorRepository $dogovorRepository */
        $dogovorRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository('ListsDogovorBundle:Dogovor');

        /** @var \Lists\DogovorBundle\Entity\Dogovor $object */
        $object = $dogovorRepository->getDogovorById($id);

        $dogovor = $dogovorRepository->find($id);

        $service = $this->get('lists_dogovor.service');
        $access = $service->checkAccess($this->getUser(), $dogovor);

        if (!$access->canSee()) {
            return $this->render('ListsDogovorBundle:Dogovor:noAccess.html.twig');
        }

        $object['isActiveChoices'] = $service->getIsActiveChoices();
        $object['prolongationChoices'] = $service->getProlongationChoices();
        $object['mashtabChoices'] = $service->getMashtabChoices();

        return $this->render('ListsDogovorBundle:Dogovor:show.html.twig', array(
            'dogovor' => $object,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'access' => $access
        ));
    }

    /**
     * Renders single element of dogovor list
     *
     * @param int $id
     *
     * @return string
     */
    public function elementAction($id)
    {
        /** @var DogovorRepository $dr */
        $dr = $this->get('lists_dogovor.repository');

        $itemQuery = $dr->getAllForDogovorQuery(array(), $id);

        $item = $itemQuery->getSingleResult();

        return $this->render('ListsDogovorBundle:' . $this->baseTemplate . ':element.html.twig', array(
            'item' => $item,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix
        ));
    }
    /**
     * Renders single element of dogovor list
     *
     * @param integer $id Organization.id
     *
     * @return string
     */
    public function forOrganizationAction($id)
    {
        /** @var DogovorRepository $dr */
        $dr = $this->get('lists_dogovor.repository');

        $dogovors = $dr->getDogovorByOrganizationCustomerPerformerId($id);

        return $this->render('ListsDogovorBundle:Dogovor:forOrganization.html.twig', array(
            'dogovors' => $dogovors
        ));
    }
}
