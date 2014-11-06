<?php

namespace ITDoors\OperBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Symfony\Component\HttpFoundation\Response;

/**
 * OperDepartmentInfoController class
 *
 * Default controller for oper page
 */
class OperDepartmentInfoController extends BaseFilterController
{

    protected $filterNamespace = 'ajax.paginator.namespace.oper.department.coworkers';

    /**
     * indexAction
     *
     * @param integer $id
     *
     * @return mixed[]
     */
    public function indexAction($id)
    {
        /** @var AccessService $accessService */
        $accessService = $this->get('access.service');
        $access = $accessService -> checkAccessToDepartment($id);
        if (!$access) {
            //return $this->redirect($this->generateUrl('it_doors_oper_homepage'));
        }



        $this->addToSessionValues('idDepartment', $id, 'param', 'oper.bundle.department');

        $repository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments');

        $department = $repository->find($id);

        $name = '';
        $mpks = $department->getMpks();
        foreach ($mpks as $mpk) {
            if ($mpk->getActive()) {
                $name = $mpk->getName().' | ';
                break;
            }
        }
        if ($department->getOrganization()) {
            $organization = $department->getOrganization()->getName();
        }
        $name .= $organization.' | '.$department->getName();


        return $this->render('ITDoorsOperBundle:DepartmentInfo:template.html.twig', array(
            'idDepartment' => $id,
            'departmentName' => $name
        ));
    }

    /**
     * basicAction
     *
     * @param int $id
     *
     * @return mixed[]
     */
    public function basicAction($id)
    {
        $accessEdit = false;
        $filterNamespace = $this->container->getParameter($this->getNamespace());
        $this->clearPaginator($filterNamespace);
        $repository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments');
        $department = $repository->getDepartmentInfoById($id);
        $oper = $this->getDoctrine()
            ->getRepository('SDUserBundle:StuffDepartments')
            ->findOneBy(array(
                'stuff' => $this->getUser()->getStuff(),
                'departments' => $id
            ));
        if ($oper || $this->getUser()->hasRole('ROLE_DOGOVORADMIN')) {
            $accessEdit = true;
        }
        $mpks = $repository->find($id)->getMpks();
        $oldMpks = false;
        foreach ($mpks as $mpk) {
            if (!$mpk->getActive()) {
                $oldMpks = true;
                break;
            }
        }

        return $this->render('ITDoorsOperBundle:DepartmentInfo:basic.html.twig', array(
            'accessEdit' => $accessEdit,
            'department' => $department,
            'mpks' => $mpks,
            'oldMpks' => $oldMpks
        ));

    }

    /**
     * departmentPeople as "coworkers" Table Action With Filter
     *
     * @param integer $id
     *
     * @return mixed[]
     */
    public function coworkersAction($id)
    {
        $this->addToSessionValues('idDepartment', $id, 'param', 'oper.bundle.department');


        $filterNamespace = $this->container->getParameter($this->getNamespace());

        $this->clearPaginator($filterNamespace);
        $filters = $this->getFilters($filterNamespace);

        $page = $this->getPaginator($filterNamespace);
        if (!$page) {
            $page = 1;
        }

        /** @var $departmentPeopleRepository \Lists\DepartmentBundle\Entity\DepartmentPeopleRepository   */
        $departmentPeopleRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople');

        $query = $departmentPeopleRepository->getFilteredDepartmentPeopleQuery($id, $filters);

        $paginator = $this->get('knp_paginator');

        $countDepartments = $departmentPeopleRepository->getFilteredDepartmentPeopleQuery($id, $filters, 'count')
                                                       ->getSingleScalarResult();

        $query->setHint('knp_paginator.count', $countDepartments);

        $pagination = $paginator->paginate(
            $query,
            $page,
            20
        );

        $counter = ($page -1)*20; //counter for raw in table

        return $this->render('ITDoorsOperBundle:DepartmentInfo:coworkers.html.twig', array(
            'pagination' => $pagination,
            'id' => $id,
            'counter' => $counter
        ));
    }

    /**
     * departmentPeople as "coworkers" Table Action
     *
     * @param integer $id
     *
     * @return mixed[]
     */
    public function coworkersTableAction($id)
    {

        $filterNamespace = $this->container->getParameter($this->getNamespace());

        $filters = $this->getFilters($filterNamespace);

        $page = $this->getPaginator($filterNamespace);
        if (!$page) {
            $page = 1;
        }

        /** @var $departmentPeopleRepository \Lists\DepartmentBundle\Entity\DepartmentPeopleRepository   */
        $departmentPeopleRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople');

        $query = $departmentPeopleRepository->getFilteredDepartmentPeopleQuery($id, $filters);

        $paginator = $this->get('knp_paginator');

        $countDepartments = $departmentPeopleRepository->getFilteredDepartmentPeopleQuery($id, $filters, 'count')
                                                       ->getSingleScalarResult();

        $query->setHint('knp_paginator.count', $countDepartments);

        $pagination = $paginator->paginate(
            $query,
            $page,
            20
        );

        $counter = ($page -1)*20; //counter for raw in table

        return $this->render('ITDoorsOperBundle:DepartmentInfo:coworkerMainTable.html.twig', array(
            'pagination' => $pagination,
            'id' => $id,
            'counter' => $counter
        ));
    }
    /**
     * resposibleAction
     *
     * @param integer $id
     *
     * @return mixed[]
     */
    public function resposibleAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $department = $em->getRepository('ListsDepartmentBundle:Departments')->find($id);
        if (!$department) {
            throw new \Exception('Departmen not found', 404);
        }

        $stuffDepartments = $em->getRepository('SDUserBundle:StuffDepartments')
            ->findBy(array('departments' => $department));

        $result = array();
        foreach ($stuffDepartments as $stuffDepartment) {
            $idStuff = $stuffDepartment->getStuff()->getId();
            $userkey = $stuffDepartment->getUserkey();
            if (!isset($result[$idStuff])) {
                $result[$idStuff] = array();
            }
            if (!isset($result[$idStuff]['userkey'])) {
                $result[$idStuff]['userkey'] = array();
            }
            if (!isset($result[$idStuff]['userkey'][$userkey])) {
                $result[$idStuff]['userkey'][$userkey] = array();
            }
            $result[$idStuff]['userkey'][$userkey][] = $stuffDepartment;
            $result[$idStuff]['user'] = $stuffDepartment->getStuff()->getUser();
            $result[$idStuff]['stuffId'] = $stuffDepartment->getStuff()->getId();
        }

        return $this->render('ITDoorsOperBundle:DepartmentInfo:resposible.html.twig', array(
            'stuffDepartments' => $result,
            'departmentId' => $id
        ));
    }

    /**
     * Render table for user (individual) info by id
     *
     * @param integer $id
     *
     * @return mixed[]
     */
    public function userInfoTableAction($id)
    {
        //$id = $this->get('request')->request->get('idUser');
        /** @var $departmentPeopleRepository \Lists\DepartmentBundle\Entity\DepartmentPeopleRepository   */
        $person = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople')
            ->getInfoById($id)
            ->getResult();

        $return = array();

        $plannedAccrualRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:PlannedAccrual');

        $plannedAccrual = $plannedAccrualRepository->findBy(
            array(
                'departmentPeople' => $id
            ),
            array('period' => 'DESC')
        );


        $return['html'] = $this->renderView('ITDoorsOperBundle:DepartmentInfo:coworkerInfoTable.html.twig', array(
            'person' => $person,
            'planned' => $plannedAccrual
        ));
        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * @return Response
     */
    public function getAllContactsExcelAction()
    {
        $contacts = $this->getDoctrine()
            ->getRepository('ListsContactBundle:ModelContact')
            ->getAllContacts();
        //print_r($contacts);

        $serviceExport = $this->get('itdoors_common.export.service');

        $excelObject = $serviceExport->getExcel($contacts);

        $response = $serviceExport->getResponse($excelObject, 'Contacts');

        return $response;
    }

    /**
     * Render reports for department
     *
     * @param integer $id
     *
     * @return mixed[]
     */
    public function reportListAction($id)
    {

        $department = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments')->find($id);

        $organizer = $this->getDoctrine()
            ->getRepository('ITDoorsOperBundle:OperOrganizer')->findBy(array(
                'department' => $department
            ));
        $organizerReports = array();
        if ($organizer) {
            $organizerReports = $this->getDoctrine()
                ->getRepository('ITDoorsOperBundle:CommentOrganizer')->findBy(array(
                    'organizer' => $organizer
                ));
        }

        return $this->render('ITDoorsOperBundle:DepartmentInfo:reports.html.twig', array(
            'organizerReports' => $organizerReports
        ));
    }
}
