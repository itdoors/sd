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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction ()
    {
        $isAddOrganization = false;
        $isExportToExcel = false;

        $namespase = $this->filterNamespace;
        $user = $this->getUser();
        if ($user->hasRole('ROLE_SALESADMIN') || $user->hasRole('ROLE_DOGOVORADMIN') || $user->hasRole('ROLE_CONTROLLING') || $user->hasRole('ROLE_CONTROLLING_OPER')) {
            $this->filterFormName = 'organizationSalesAdminFilterForm';
        }

        return $this->render('ListsOrganizationBundle:Organization:index.html.twig', array (
                'namespase' => $namespase,
                'isAddOrganization' => $isAddOrganization,
                'isExportToExcel' => $isExportToExcel,
                'filter' => $this->filterFormName
        ));
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction ()
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
        if ($user->hasRole('ROLE_SALESADMIN') || $user->hasRole('ROLE_DOGOVORADMIN') || $user->hasRole('ROLE_CONTROLLING') || $user->hasRole('ROLE_CONTROLLING_OPER')) {
            $userId = null;
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

        $form = $this->createForm('organizationSalesForm');
        if ($user->hasRole('ROLE_DOGOVORADMIN')) {
            $form = $this->createForm('organizationDogovorAdminForm');
        }

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
        $isEdit = true;
        $isAddContacts = false;
        $isAddManager = false;
        $isAddDocument = false;
        $isAddKVED = false;

        $this->get('sd.security_access')->hasAccessToOrganizationAndThrowException($id);

        /** @var \Lists\OrganizationBundle\Entity\Organization $organization */
        $organization = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($id);

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
                'isEdit' => $isEdit,
                'isAddManager' => $isAddManager,
                'isAddContacts' => $isAddContacts,
                'isAddDocument' => $isAddDocument,
                'isAddKVED' => $isAddKVED
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
}
