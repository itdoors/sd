<?php

namespace ITDoors\OperBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Symfony\Component\HttpFoundation\Response;

/**
 * OperInfoController class
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
        $this->addToSessionValues('idDepartment', $id, 'param', 'oper.bundle.department', 'param');

        $repository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments');

        $department = $repository->find($id);
        $name = $department->getName();

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
        $filterNamespace = $this->container->getParameter($this->getNamespace());
        $this->clearPaginator($filterNamespace);
        $repository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments');

        $department = $repository->getDepartmentInfoById($id);
        $mpks = $repository->find($id)->getMpks();
        $oldMpks = false;
        foreach ($mpks as $mpk) {
            if (!$mpk->getActive()) {
                $oldMpks = true;
                break;
            }
        }

        return $this->render('ITDoorsOperBundle:DepartmentInfo:basic.html.twig', array(
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

        $return['html'] = $this->renderView('ITDoorsOperBundle:DepartmentInfo:coworkerInfoTable.html.twig', array(
            'person' => $person,
        ));
        $return['success'] = 1;

        return new Response(json_encode($return));
    }
}
