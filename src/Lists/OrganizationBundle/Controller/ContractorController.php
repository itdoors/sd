<?php

namespace Lists\OrganizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Lists\OrganizationBundle\Controller\SalesController;
use Lists\OrganizationBundle\Entity\OrganizationUser;

/**
 * Class ContractorController
 */
class ContractorController extends SalesController
{

    protected $filterNamespace = 'organization.contractor.filters';
    protected $baseRoutePrefix = 'contractor';
    protected $baseTemplate = 'Contractor';

    protected $filterFormName = 'organizationContractorFilterFormType';


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $namespase = $this->filterNamespace;
        $filter = $this->filterFormName;

        return $this->render('ListsOrganizationBundle:Contractor:index.html.twig', array(
                'namespase' => $namespase,
                'filter' => $filter,
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
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
        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        /** @var Lookup $lookup */
        $lookup = $this->getDoctrine()
                ->getRepository('ListsLookupBundle:Lookup')
                ->findOneBy(array('lukey' => 'organization_sign_contractor'));

        /** @var \Doctrine\ORM\Query */
        $organizationsQuery = $organizationsRepository->getContractor(null, $lookup->getId(), $filters);

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $organizationsQuery,
            $page,
            20
        );

        return $this->render('ListsOrganizationBundle:Contractor:list.html.twig', array(
                'pagination' => $pagination,
                'namespase' => $namespase,
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix
        ));
    }

    /**
     * Executes show action
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        if (!$this->getUser()->hasRole('ROLE_OPER')) {
            $this->get('sd.security_access')->hasAccessToOrganizationAndThrowException($id);
        }
        /** @var \Lists\OrganizationBundle\Entity\Organization $organization */
        $organization= $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($id);

        if ($organization->getParent()) {
            return $this->redirect($this->generateUrl('lists_' . $this->baseRoutePrefix . '_organization_show', array(
                    'id' => $organization->getParentId()
                )));
        }

        $managerForm = $this->createForm('organizationUserForm');

        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate. ':show.html.twig', array(
            'organization' => $organization,
            'filterFormName' => $this->filterFormName,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'managerForm' => $managerForm->createView()
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
        $form = $this->createForm('organizationSalesForm');

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var \Lists\OrganizationBundle\Entity\Organization $organization */
            $organization = $form->getData();

            $user = $this->getUser();

            $organization->setCreator($user);

            $em = $this->getDoctrine()->getManager();

            /** @var Lookup $lookup */
            $lookup = $em
                ->getRepository('ListsLookupBundle:Lookup')
                ->findOneBy(array('lukey' => 'organization_sign_contractor'));

            $organization->setLookup($lookup);

            $em->persist($organization);
            $lookupM = $this->getDoctrine()->getRepository('ListsLookupBundle:lookup')->findOneBy(array('lukey' => 'manager_organization'));
            $manager = new OrganizationUser();
            $manager->setOrganization($organization);
            $manager->setUser($user);
            $manager->setLookup($lookupM);
            $em->persist($manager);

            $em->flush();

            return $this->redirect($this->generateUrl('lists_' . $this->baseRoutePrefix . '_show', array(
                        'id' => $organization->getId()
            )));
        }

        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate . ':new.html.twig', array(
                'filterForm' => $form->createView(),
                'filterFormName' => $this->filterFormName,
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }

    /**
     * Renders organizationUsers list
     *
     * @param int $organizationId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function organizationUsersAction($organizationId)
    {
        /** @var \SD\UserBundle\Entity\UserRepository $ur */
        $ur = $this->container->get('sd_user.repository');

        $organizationUsers = $ur->getOrganizationUsersQuery($organizationId)
            ->getQuery()
            ->getResult();

        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate . ':organizationUsers.html.twig', array(
                'organizationUsers' => $organizationUsers,
                'organizationId' => $organizationId
        ));
    }

    /**
     * @param integer $id
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listCoeaAction($id)
    {
        /** @var Coea $coea */
        $coea = $this->getDoctrine()
                ->getRepository('ListsOrganizationBundle:Coea');

        $coeas = $coea->getCoeaForContractorId($id);

        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate . ':listCoea.html.twig', array(
            'coea' => $coeas,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix
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


        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate . ':listKved.html.twig', array(
            'kveds' => $kveds,
            'organizationId' => $id,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix
        ));
    }


    /**
     * @param integer $id
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listDogovorAction($id)
    {
        /** @var DogovorRepository $dr */
        $dr = $this->get('lists_dogovor.repository');

        $dogovors = $dr->getDogovorsForContractorId($id);

        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate . ':listDogovor.html.twig', array(
            'dogovors' => $dogovors,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix
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

        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate . ':listDocument.html.twig', array(
            'documents' => $docs,
            'organizationId' => $id,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix
        ));
    }

}
