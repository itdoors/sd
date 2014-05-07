<?php

namespace ITDoors\OperBundle\Controller;

use ITDoors\CommonBundle\Controller\BaseFilterController;

/**
 * OperInfoController class
 *
 * Default controller for oper page
 */
class OperInfoController extends BaseFilterController
{
    protected $filterNamespace = 'report.sales.admin.filters';
    protected $filterFormName = 'reportSalesAdminFilterForm';

    /**
     * indexAction
     *
     * @return mixed[]
     */
    public function indexAction()
    {
        $page = $this->get('request')->query->get('page', 1);
        $this->addToFilters('page', $page);

        return $this->render('ITDoorsOperBundle:Patterns:index.html.twig', array(

        ));
    }

    /**
     * departmentAction
     *
     * @return mixed[]
     */
    public function departmentAction()
    {
        $page = $this->container->getParameter('ajax.paginator.namespace.oper.department.table');
        if (!$page) {
            $page = 1;
        }

        //$this->refreshFiltersIfAjax();
        $page = $this->getFilterValueByKey('page', 1);

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        /*
          $departments = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments')
            ->findAll();
        */
        $repository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments');

        $query = $repository->getAllDepartmentsQuery();

        $countDepartments = $repository->countAllDepartments();

        $query->setHint('knp_paginator.count', $countDepartments);
        $pagination = $paginator->paginate(
            $query,
            $page,
            20
        );


        return $this->render('ITDoorsOperBundle:Parts:department.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * departmentTableAction
     *
     * @return mixed[]
     */
    public function departmentTableAction()
    {

        $paginationNamespace = $this->container->getParameter('ajax.paginator.namespace.oper.department.table');

        $filterNamespace = $this->container->getParameter('ajax.filter.namespace.oper.department.table');

        $filters = $this->getFilters($filterNamespace);

        $page = $this->getFilterValueByKey($paginationNamespace, 1);

        $departmentsRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments');


        $query = $departmentsRepository->getFilteredDepartments($filters);

        $paginator  = $this->get('knp_paginator');

        $countDepartments = $departmentsRepository->getFilteredDepartments($filters, "count")->getSingleScalarResult();

        $query->setHint('knp_paginator.count', $countDepartments);

        $pagination = $paginator->paginate(
            $query,
            $page,
            20
        );

        return $this->render('ITDoorsOperBundle:Parts:department.html.twig', array(
            'pagination' => $pagination
        ));
    }
}
